<?php

namespace App\Http\Controllers;

use App\Models\SmsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * نمایش فرم ارسال پیامک تکی.
     *
     * @return \Illuminate\View\View
     */
    public function sendSingleSms()
    {
        return view('user.sms.send-single');
    }

    /**
     * پردازش و ارسال پیامک تکی.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

public function storeSingleSms(Request $request)
{
    // اعتبارسنجی ورودی‌ها
    $request->validate([
        'line_number' => 'required|exists:lines,line_number',
        'numbers'     => 'required|array',
        'numbers.*'   => 'required|regex:/^09[0-9]{9}$/',
        'message'     => 'required|string|max:1000',
    ]);

    // استخراج داده‌ها از درخواست
    $lineNumber = $request->line_number;
    $numbers    = $request->numbers;
    $message    = $request->message;
    $user       = auth()->user();

    // محاسبه تعداد پیامک‌ها
    $isPersian  = preg_match('/[\p{Arabic}]/u', $message);
    $smsLength  = $isPersian ? 70 : 160;
    $smsCount   = ceil(mb_strlen($message) / $smsLength);
    $totalSms   = $smsCount * count($numbers);

    // بررسی مانده اعتبار
    if ($user->sms_balance < $totalSms) {
        return back()->with('error', 'مانده‌ی اعتبار شما کافی نیست.');
    }

    try {
        // ساخت URL با جایگزینی API key
        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/send.json";

        // تبدیل آرایه شماره‌ها به رشته با کاما جدا شده
        $to = implode(',', $numbers);

        // آماده‌سازی پارامترها
        $queryParams = [
            'gateway' => $lineNumber,
            'to'      => $to,
            'text'    => $message,
        ];

        // ارسال درخواست با Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url, [
            'query' => $queryParams,
        ]);

        // لاگ کردن درخواست برای دیباگ
        Log::info('SMS request sent', [
            'user_id'     => $user->id,
            'url'         => $url,
            'query_params' => $queryParams,
        ]);

        // بررسی پاسخ
        $result = json_decode($response->getBody(), true);

        if (is_null($result)) {
            Log::error('Invalid JSON response from Sabanovin', [
                'body' => $response->getBody()->getContents(),
            ]);
            return back()->with('error', 'خطا در ارسال پیامک: پاسخ سرور نامعتبر است.');
        }

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            Log::info('SMS sent successfully', [
                'user_id' => $user->id,
                'response' => $result,
            ]);

            // انجام تراکنش برای کسر اعتبار و ذخیره گزارش
            DB::transaction(function () use ($user, $totalSms, $lineNumber, $numbers, $message, $result) {
                // کسر اعتبار و جلوگیری از منفی شدن
                $user->sms_balance = max(0, $user->sms_balance - $totalSms);
                $user->save();

                // گرفتن batch_id از پاسخ API
                $batchId = $result['batch_id'] ?? 'unknown';

                // اطمینان از اینکه $numbers آرایه است
                if (!is_array($numbers)) {
                    $numbers = explode(',', $numbers);
                }

                // لاگ کردن قبل از ذخیره
                Log::info('Saving SMS report', [
                    'user_id' => $user->id,
                    'numbers' => $numbers,
                ]);

                // ذخیره گزارش (بدون json_encode اضافی)
                $report = new SmsReport([
                    'user_id'     => $user->id,
                    'type'        => 'single',
                    'line_number' => $lineNumber,
                    'numbers'     => $numbers, // مستقیم آرایه رو ذخیره می‌کنیم
                    'message'     => $message,
                    'sms_count'   => $totalSms,
                    'batch_id'    => $batchId,
                ]);
                $report->save();

                // لاگ کردن مقدار ذخیره‌شده
                Log::info('SMS report saved', [
                    'report_id' => $report->id,
                    'numbers'   => $report->numbers,
                ]);
            });

            return redirect()->route('reports.index')->with('success', 'پیامک با موفقیت ارسال شد.');
        }

        // لاگ کردن خطا
        Log::error('Failed to send SMS', [
            'status'  => $result['status'] ?? 'unknown',
            'body'    => $response->getBody()->getContents(),
            'headers' => $response->getHeaders(),
            'result'  => $result,
        ]);

        // ترکیب پیام خطای API (اگر وجود داشته باشد) با پیام پیش‌فرض
        $errorMessage = $result['status']['message'] ?? 'خطای ناشناخته (کد: ' . ($result['status']['code'] ?? 'نامشخص') . ')';

        return back()->with('error', 'خطا در ارسال پیامک: ' . $errorMessage);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // مدیریت خطاهای درخواست (مثلاً مشکل در اتصال یا پاسخ نامعتبر)
        Log::error('Request exception while sending SMS', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
            'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
        ]);

        return back()->with('error', 'خطا در ارسال پیامک: ' . ($e->getMessage() ?? 'مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.'));
    } catch (\Exception $e) {
        // مدیریت خطاهای غیرمنتظره
        Log::error('Exception while sending SMS', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return back()->with('error', 'خطا در ارسال پیامک: مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.');
    }
}
}