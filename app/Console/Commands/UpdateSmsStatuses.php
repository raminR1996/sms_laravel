<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSingleSmsStatus;
use App\Jobs\UpdateGroupSmsStatus;
use App\Models\SmsReport;
use App\Models\GroupSmsRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateSmsStatuses extends Command
{
    protected $signature = 'sms:update-statuses';
    protected $description = 'Update SMS statuses for single and group reports';

    public function handle()
    {
        // گزارش‌های تکی که وضعیت آنها هنوز به‌روزرسانی نشده یا نیاز به به‌روزرسانی دارند
        $singleReports = SmsReport::where('type', 'single')
            ->where(function ($query) {
                $query->whereNull('statuses')
                      ->orWhereRaw('JSON_LENGTH(statuses) < JSON_LENGTH(numbers)');
            })
            ->get();

        // گزارش‌های گروهی که تأیید شده‌اند و نیاز به به‌روزرسانی دارند
        $groupReports = GroupSmsRequest::where('status', 'approved')
            ->whereNotNull('batch_id')
            ->where(function ($query) {
                $query->whereNull('statuses')
                      ->orWhereRaw('JSON_LENGTH(statuses) < (SELECT COUNT(*) FROM contacts WHERE village_id IN (JSON_UNQUOTE(JSON_EXTRACT(village_ids, "$[*]"))))');
            })
            ->get();

        $delay = 0;
        $requestsPerMinute = 100;
        $delayIncrement = 60 / $requestsPerMinute; // 0.6 ثانیه به ازای هر درخواست

        // توزیع Job‌های گزارش‌های تکی
        foreach ($singleReports as $report) {
            UpdateSingleSmsStatus::dispatch($report->id)->delay(now()->addSeconds($delay));
            $delay += $delayIncrement;
            Log::info('Dispatched UpdateSingleSmsStatus job', [
                'report_id' => $report->id,
                'delay' => $delay,
            ]);
        }

        // توزیع Job‌های گزارش‌های گروهی
        foreach ($groupReports as $report) {
            UpdateGroupSmsStatus::dispatch($report->id)->delay(now()->addSeconds($delay));
            $delay += $delayIncrement;
            Log::info('Dispatched UpdateGroupSmsStatus job', [
                'report_id' => $report->id,
                'delay' => $delay,
            ]);
        }

        $this->info('SMS status update jobs dispatched successfully.');
    }
}