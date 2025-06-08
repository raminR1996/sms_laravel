<?php

namespace App\Jobs;

use App\Models\GroupSmsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class UpdateGroupSmsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $groupSmsRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(GroupSmsRequest $groupSmsRequest)
    {
        $this->groupSmsRequest = $groupSmsRequest;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->groupSmsRequest->status !== 'approved') {
            Log::warning('Group SMS request not approved', [
                'request_id' => $this->groupSmsRequest->id,
            ]);
            return;
        }

        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/status.json";
        $client = new Client();

        $numbers = \App\Models\Contact::whereIn('village_id', $this->groupSmsRequest->village_ids)
            ->pluck('mobile_number')
            ->toArray();
        $statuses = [];
        $datetimes = [];
        $batchIds = is_array($this->groupSmsRequest->batch_id)
            ? $this->groupSmsRequest->batch_id
            : (is_string($this->groupSmsRequest->batch_id) ? json_decode($this->groupSmsRequest->batch_id, true) : [$this->groupSmsRequest->batch_id]);

        if (empty($batchIds)) {
            Log::error('No batch_id found for group SMS request', [
                'request_id' => $this->groupSmsRequest->id,
            ]);
            return;
        }

        foreach ($batchIds as $index => $batchId) {
            $batchId = (string)$batchId;
            $cacheKey = "sms_status_{$batchId}";

            try {
                // تأخیر برای رعایت محدودیت 100 درخواست در دقیقه (600ms بین درخواست‌ها)
                if ($index > 0) {
                    usleep(600000); // 600 میلی‌ثانیه تأخیر
                }

                // بررسی کَش
                if (Cache::has($cacheKey)) {
                    $result = Cache::get($cacheKey);
                } else {
                    $response = $client->get($url, [
                        'query' => ['batch_id' => $batchId],
                        'headers' => ['Jalali-DateTime' => '1'],
                    ]);

                    $result = json_decode($response->getBody(), true);
                    Cache::put($cacheKey, $result, now()->addMinutes(10));

                    if (is_null($result) || !isset($result['status']['code']) || $result['status']['code'] != 200) {
                        throw new \Exception($result['status']['message'] ?? 'خطا در دریافت وضعیت.');
                    }
                }

                if (isset($result['entries']) && is_array($result['entries'])) {
                    foreach ($numbers as $numberIndex => $number) {
                        foreach ($result['entries'] as $entry) {
                            if (isset($entry['number']) && $entry['number'] == $number) {
                                $statuses[$numberIndex] = $entry['status'] ?? 'unknown';
                                $datetimes[$numberIndex] = $entry['datetime'] ?? null;
                                break;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error retrieving SMS status for batch', [
                    'request_id' => $this->groupSmsRequest->id,
                    'batch_id' => $batchId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        if (!empty($statuses)) {
            $this->groupSmsRequest->update([
                'statuses' => $statuses,
                'datetimes' => $datetimes,
            ]);
            Log::info('Group SMS status updated successfully', [
                'request_id' => $this->groupSmsRequest->id,
                'batch_ids' => $batchIds,
            ]);
        } else {
            Log::warning('No statuses retrieved for group SMS request', [
                'request_id' => $this->groupSmsRequest->id,
            ]);
        }
    }
}