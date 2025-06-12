<?php

namespace App\Http\Controllers;

use App\Models\SmsReport;
use App\Models\Transaction;
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
    $request->validate([
        'line_number' => 'required|exists:lines,line_number',
        'numbers'     => 'required|array|max:100', // محدود کردن به 100 شماره
        'numbers.*'   => 'required|regex:/^09[0-9]{9}$/',
        'message'     => 'required|string|max:1000',
    ]);

    $lineNumber = $request->line_number;
    $numbers    = $request->numbers;
    $message    = $request->message;
    $user       = auth()->user();

    $isPersian  = preg_match('/[\p{Arabic}]/u', $message);
    $smsLength  = $isPersian ? 70 : 160;
    $smsCount   = ceil(mb_strlen($message) / $smsLength);
    $totalSms   = $smsCount * count($numbers);

    if ($user->sms_balance < $totalSms) {
        return back()->with('error', 'مانده‌ی اعتبار شما کافی نیست.');
    }

    try {
        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/send.json";
        $client = new \GuzzleHttp\Client();

        // ارسال درخواست
        $to = implode(',', $numbers);
        $queryParams = [
            'gateway' => $lineNumber,
            'to'      => $to,
            'text'    => $message,
        ];

        $response = $client->get($url, [
            'query' => $queryParams,
        ]);

        $result = json_decode($response->getBody(), true);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            DB::transaction(function () use ($user, $totalSms, $lineNumber, $numbers, $message, $result) {
                $user->sms_balance = max(0, $user->sms_balance - $totalSms);
                $user->save();

                $batchId = $result['batch_id'] ?? 'unknown';

                $report = new SmsReport([
                    'user_id'     => $user->id,
                    'type'        => 'single',
                    'line_number' => $lineNumber,
                    'numbers'     => $numbers,
                    'message'     => $message,
                    'sms_count'   => $totalSms,
                    'batch_id'    => $batchId,
                ]);
                $report->save();

                // ثبت تراکنش
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'sms_count' => $totalSms,
                    'amount' => null,
                    'description' => "ارسال پیامک تکی  " . count($numbers) . " شماره",
                    'sms_balance_after' => $user->sms_balance,
                ]);
            });

            return redirect()->route('reports.index')->with('success', 'پیامک با موفقیت ارسال شد.');
        }

        return back()->with('error', 'خطا در ارسال پیامک: ' . ($result['status']['message'] ?? 'خطای ناشناخته'));
    } catch (\Exception $e) {
        Log::error('Exception while sending SMS', [
            'user_id' => $user->id,
            'error'   => $e->getMessage(),
        ]);
        return back()->with('error', 'خطا در ارسال پیامک: مشکل در اتصال به سرور.');
    }
}
}