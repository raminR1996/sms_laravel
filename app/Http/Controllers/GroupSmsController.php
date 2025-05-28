<?php

namespace App\Http\Controllers;

use App\Models\GroupSmsRequest;
use App\Models\Line;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GroupSmsController extends Controller
{
    /**
     * نمایش فرم ارسال پیامک گروهی.
     */
    public function sendGroupSms()
    {
        $villages = Village::withCount('contacts')->get(); // روستاها با تعداد شماره‌ها
        $lines = Line::where('is_active', true)->get(); // خطوط فعال
        return view('user.sms.send-group', compact('villages', 'lines'));
    }

    /**
     * پردازش و ثبت درخواست ارسال پیامک گروهی.
     */
    public function storeGroupSms(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'line_number' => 'required|exists:lines,line_number',
            'village_ids' => 'required|array',
            'village_ids.*' => 'exists:villages,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $lineNumber = $request->line_number;
        $villageIds = $request->village_ids;
        $message = $request->message;

        // محاسبه تعداد پیامک‌ها
        $isPersian = preg_match('/[\p{Arabic}]/u', $message);
        $smsLength = $isPersian ? 70 : 160;
        $smsCountPerMessage = ceil(mb_strlen($message) / $smsLength);

        // تعداد شماره‌های مرتبط با روستاها
        $totalNumbers = \App\Models\Contact::whereIn('village_id', $villageIds)->count();
        $totalSms = $smsCountPerMessage * $totalNumbers;

        // بررسی مانده اعتبار
        if ($user->sms_balance < $totalSms) {
            return back()->with('error', 'مانده‌ی اعتبار شما کافی نیست.');
        }

        try {
            // ثبت درخواست در دیتابیس
            DB::transaction(function () use ($user, $lineNumber, $villageIds, $message, $totalSms) {
                GroupSmsRequest::create([
                    'user_id' => $user->id,
                    'line_number' => $lineNumber,
                    'message' => $message,
                    'village_ids' => $villageIds,
                    'sms_count' => $totalSms,
                    'status' => 'pending',
                ]);
            });

            return redirect()->route('group.reports.index')->with('success', 'درخواست ارسال پیامک گروهی ثبت شد و منتظر تأیید مدیر است.');
        } catch (\Exception $e) {
            Log::error('Error storing group SMS request', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'خطا در ثبت درخواست. لطفاً دوباره تلاش کنید.');
        }
    }

   
}