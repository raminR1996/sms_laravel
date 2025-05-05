<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showOtpForm()
    {
        return view('auth.otp-login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone_number' => 'required|regex:/^09[0-9]{9}$/']);
        $phoneNumber = $request->phone_number;
        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);
        OtpCode::create(['phone_number' => $phoneNumber, 'code' => $code, 'expires_at' => $expiresAt]);

        $apiKey = env('SABANOVIN_API_KEY');
        $gateway = "50003190"; // خط خودت رو بذار
        $text = "کد ورود شما: $code   لغو11";
        $to = [$phoneNumber];

        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api.sabanovin.com/v1/{$apiKey}/sms/send.json", [
            'query' => ['gateway' => $gateway, 'to' => implode(',', $to), 'text' => $text]
        ]);

        $result = json_decode($response->getBody(), true);
        if ($result['status']['code'] == 200) {
            Session::put('phone_number', $phoneNumber);
            return redirect()->route('otp.verify')->with('success', 'کد OTP ارسال شد!');
        } else {
            return back()->withErrors(['error' => 'ارسال پیامک ناموفق بود!']);
        }
    }

    public function showVerifyForm()
    {
        return view('auth.otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $phoneNumber = Session::get('phone_number');
        $otp = OtpCode::where('phone_number', $phoneNumber)
                      ->where('code', $request->code)
                      ->where('is_used', false)
                      ->where('expires_at', '>', Carbon::now())
                      ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'کد نامعتبر یا منقضی شده!']);
        }

        $otp->update(['is_used' => true]);
        $user = \App\Models\User::firstOrCreate(['phone_number' => $phoneNumber], ['name' => 'کاربر ' . $phoneNumber, 'phone_number' => $phoneNumber]);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'ورود موفق!');
    }
}