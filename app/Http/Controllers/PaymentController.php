<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $merchantId;
    protected $sandboxMode;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.zarinpal.merchant_id', '72ff488f-9c4d-481b-a9f7-ff40e51f42f9');
        $this->sandboxMode = config('services.zarinpal.sandbox_mode', false);
        $this->baseUrl = $this->sandboxMode
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://api.zarinpal.com/pg/v4/payment';
    }

    /**
     * نمایش صفحه خرید بسته‌ها و تاریخچه پرداخت‌ها
     */
    public function index()
    {
        $packages = Package::where('is_active', true)->get();
        $payments = Payment::where('user_id', Auth::id())
            ->where('status', 'success')
            ->latest()
            ->get();
        $userPackages = UserPackage::where('user_id', Auth::id())
            ->with('package')
            ->latest()
            ->get();

        return view('user.charge', compact('packages', 'payments', 'userPackages'));
    }

  /**
 * پردازش درخواست خرید بسته و هدایت به درگاه پرداخت
 */
public function purchase(Request $request)
{
    try {
        Log::info('Starting purchase process', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        // اعتبارسنجی ورودی
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('کاربر احراز هویت نشده است.');
        }

        // ثبت پرداخت موقت
        $payment = $this->createPendingPayment($user->id, $package);

        // ذخیره package_id در دیتابیس
        $payment->update(['package_id' => $package->id]);

        // درخواست به زرین‌پال
        $response = $this->requestPayment($package, $user, $payment);

        if ($response->successful() && isset($response->json()['data']['authority'])) {
            $authority = $response->json()['data']['authority'];
            $payment->update(['transaction_id' => $authority]);

            $paymentUrl = $this->sandboxMode
                ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
                : "https://www.zarinpal.com/pg/StartPay/{$authority}";

            Log::info('Redirecting to Zarinpal', ['payment_url' => $paymentUrl]);
            return redirect($paymentUrl);
        }

        $this->handlePaymentError($payment, $response->json());
    } catch (\Exception $e) {
        $this->logAndRedirectError($e, $payment ?? null, 'Purchase error');
    }
}
 /**
 * پردازش پاسخ درگاه پرداخت
 */
public function callback(Request $request)
{
    try {
        Log::info('Callback request received', ['request' => $request->all()]);

        $authority = $request->input('Authority');
        $status = $request->input('Status');

        if (!$authority || !$status) {
            throw new \Exception('داده‌های Authority یا Status دریافت نشده است.');
        }

        $payment = Payment::where('transaction_id', $authority)->first();
        if (!$payment) {
            throw new \Exception("تراکنش یافت نشد. Authority: {$authority}");
        }

        if ($status !== 'OK') {
            $payment->update(['status' => 'failed']);
            throw new \Exception("پرداخت لغو شد. Status: {$status}");
        }

        $response = $this->verifyPayment($payment, $authority);
        if ($response->successful() && isset($response->json()['data']['code']) && $response->json()['data']['code'] == 100) {
            $this->processSuccessfulPayment($payment);
            return redirect()->route('charge.index')->with('success', 'پرداخت با موفقیت انجام شد.');
        }

        $this->handlePaymentError($payment, $response->json());
    } catch (\Exception $e) {
        if (isset($payment)) {
            $payment->update(['status' => 'failed']);
        }
        Log::error('Callback error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'authority' => $authority ?? 'N/A',
            'request' => $request->all(),
        ]);

        // اگر کاربر وارد نشده باشد، مستقیماً یک پاسخ JSON برگردانیم
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }

        return redirect()->route('charge.index')->with('error', 'خطا: ' . $e->getMessage());
    }
}
    /**
     * ثبت پرداخت موقت
     */
    protected function createPendingPayment($userId, Package $package)
    {
        $payment = Payment::create([
            'user_id' => $userId,
            'amount' => $package->price,
            'transaction_id' => uniqid('tx_'),
            'status' => 'pending',
        ]);

        Log::info('Payment created', [
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
        ]);

        return $payment;
    }

  /**
 * ارسال درخواست پرداخت به زرین‌پال
 */
protected function requestPayment(Package $package, $user, Payment $payment)
{
    $callbackUrl = config('services.zarinpal.callback_url', route('payment.callback'));
    $description = "خرید بسته {$package->sms_count} پیامکی";
    $mobile = $user->phone_number ?? '09120000000';
    $email = $user->email ?? 'user@example.com';

    Log::info('Zarinpal request data', [
        'merchant_id' => $this->merchantId,
        'amount' => $package->price,
        'description' => $description,
        'callback_url' => $callbackUrl,
        'mobile' => $mobile,
        'email' => $email,
    ]);

    return Http::post("{$this->baseUrl}/request.json", [
        'merchant_id' => $this->merchantId,
        'amount' => $package->price,
        'description' => $description,
        'callback_url' => $callbackUrl,
        'mobile' => $mobile,
        'email' => $email,
    ]);
}

   /**
 * تأیید پرداخت از زرین‌پال
 */
protected function verifyPayment(Payment $payment, string $authority)
{
    $response = Http::post("{$this->baseUrl}/verify.json", [
        'merchant_id' => $this->merchantId,
        'authority' => $authority,
        'amount' => $payment->amount,
    ]);

    Log::info('Zarinpal verify response', [
        'status' => $response->status(),
        'result' => $response->json(),
    ]);

    return $response;
}

    /**
     * پردازش پرداخت موفق
     */
    protected function processSuccessfulPayment(Payment $payment)
    {
        $payment->update(['status' => 'success']);

        $package = Package::find($payment->package_id);
        if (!$package) {
            Log::warning('Package not found', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
            ]);
            return;
        }

        UserPackage::create([
            'user_id' => $payment->user_id,
            'package_id' => $package->id,
            'payment_id' => $payment->id,
            'sms_count' => $package->sms_count,
            'price' => $package->price,
        ]);

        $user = $payment->user;
        $user->increment('sms_balance', $package->sms_count);

        Log::info('Package purchased successfully', [
            'user_id' => $payment->user_id,
            'package_id' => $package->id,
            'sms_count' => $package->sms_count,
        ]);
    }

    /**
     * مدیریت خطاهای پرداخت
     */
    protected function handlePaymentError(Payment $payment, $response)
    {
        $errorCode = $response['errors']['code'] ?? 'N/A';
        $errorMessage = $response['errors']['message'] ?? 'خطای ناشناخته در درخواست پرداخت.';
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }
        throw new \Exception("خطای زرین‌پال (کد: {$errorCode}): {$errorMessage}");
    }

    /**
     * لاگ‌گذاری و هدایت در صورت خطا
     */
    protected function logAndRedirectError(\Exception $e, ?Payment $payment, string $context, array $extra = [])
    {
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        Log::error($context, array_merge([
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'N/A',
        ], $extra));

        return redirect()->route('charge.index')->with('error', 'خطا: ' . $e->getMessage());
    }
}