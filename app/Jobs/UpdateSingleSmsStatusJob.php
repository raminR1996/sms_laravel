<?php

namespace App\Jobs;

use App\Models\SmsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class UpdateSingleSmsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $smsReport;

    /**
     * Create a new job instance.
     */
    public function __construct(SmsReport $smsReport)
    {
        $this->smsReport = $smsReport;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/status.json";
        $client = new Client();
        $cacheKey = "sms_status_{$this->smsReport->batch_id}";

        try {
            // بررسی کَش
            if (Cache::has($cacheKey)) {
                $result = Cache::get($cacheKey);
            } else {
                // تأخیر برای رعایت محدودیت 100 درخواست در دقیقه (600ms)
                usleep(600000);

                $response = $client->get($url, [
                    'query' => ['batch_id' => $this->smsReport->batch_id],
                    'headers' => ['Jalali-DateTime' => '1'],
                ]);

                $result = json_decode($response->getBody(), true);
                Cache::put($cacheKey, $result, now()->addMinutes(10));

                if (is_null($result) || !isset($result['status']['code']) || $result['status']['code'] != 200) {
                    throw new \Exception($result['status']['message'] ?? 'خطا در دریافت وضعیت.');
                }
            }

            $statuses = [];
            $datetimes = [];
            if (isset($result['entries']) && is_array($result['entries'])) {
                foreach ($this->smsReport->numbers as $index => $number) {
                    $status = 'unknown';
                    $datetime = null;
                    foreach ($result['entries'] as $entry) {
                        if (isset($entry['number']) && $entry['number'] == $number) {
                            $status = $entry['status'] ?? 'unknown';
                            $datetime = $entry['datetime'] ?? null;
                            break;
                        }
                    }
                    $statuses[$index] = $status;
                    $datetimes[$index] = $datetime;
                }
            }

            if (!empty($statuses)) {
                $this->smsReport->update([
                    'statuses' => $statuses,
                    'datetimes' => $datetimes,
                ]);
                Log::info('Single SMS status updated successfully', [
                    'report_id' => $this->smsReport->id,
                    'batch_id' => $this->smsReport->batch_id,
                ]);
            } else {
                Log::warning('No statuses retrieved for single SMS report', [
                    'report_id' => $this->smsReport->id,
                    'batch_id' => $this->smsReport->batch_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving SMS status for single report', [
                'report_id' => $this->smsReport->id,
                'batch_id' => $this->smsReport->batch_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}