<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendGroupSmsJob;
use App\Models\GroupSmsRequest;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupSmsController extends Controller
{
    /**
     * نمایش لیست درخواست‌های پیامک گروهی.
     */
    public function index()
    {
        $requests = GroupSmsRequest::with(['user', 'approver'])->get();
        return view('admin.sms.group-sms-requests', compact('requests'));
    }

    /**
     * تأیید یا رد درخواست پیامک گروهی.
     */
    public function approve(Request $request, GroupSmsRequest $groupSmsRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        try {
            DB::transaction(function () use ($request, $groupSmsRequest) {
                if ($request->status === 'approved') {
                    // دریافت شماره‌های مرتبط با روستاها
                    $numbers = \App\Models\Contact::whereIn('village_id', $groupSmsRequest->village_ids)
                        ->pluck('mobile_number')
                        ->toArray();

                    if (empty($numbers)) {
                        throw new \Exception('هیچ شماره‌ای برای ارسال یافت نشد.');
                    }

                    // به‌روزرسانی وضعیت درخواست
                    $groupSmsRequest->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    // کسر اعتبار
                    $user = $groupSmsRequest->user;
                    $user->sms_balance = max(0, $user->sms_balance - $groupSmsRequest->sms_count);
                    $user->save();

                    // ارسال Job برای پردازش پیامک‌ها به‌صورت دسته‌ای
                    SendGroupSmsJob::dispatch($groupSmsRequest, $numbers);
                } else {
                    // رد درخواست
                    $groupSmsRequest->update([
                        'status' => 'rejected',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                }
            });

            return redirect()->route('admin.group-sms-requests')->with('success', 'وضعیت درخواست با موفقیت به‌روزرسانی شد.');
        } catch (\Exception $e) {
            Log::error('Error approving/rejecting group SMS', [
                'request_id' => $groupSmsRequest->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'خطا در پردازش درخواست: ' . $e->getMessage());
        }
    }
}