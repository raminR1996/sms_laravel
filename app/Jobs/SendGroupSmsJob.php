<?php

namespace App\Jobs;

use App\Models\GroupSmsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class SendGroupSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $groupSmsRequest;
    protected $numbers;

    /**
     * Create a new job instance.
     */
    public function __construct(GroupSmsRequest $groupSmsRequest, array $numbers)
    {
        $this->groupSmsRequest = $groupSmsRequest;
        $this->numbers = $numbers;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/send.json";
        $client = new Client();

        // تقسیم شماره‌ها به دسته‌های 100تایی
        $chunks = array_chunk($this->numbers, 100);

        foreach ($chunks as $chunk) {
            try {
                $to = implode(',', $chunk);
                $queryParams = [
                    'gateway' => $this->groupSmsRequest->line_number,
                    'to' => $to,
                    'text' => $this->groupSmsRequest->message,
                ];

                $response = $client->get($url, ['query' => $queryParams]);
                $result = json_decode($response->getBody(), true);

                if (!isset($result['status']['code']) || $result['status']['code'] != 200) {
                    throw new \Exception($result['status']['message'] ?? 'خطا در ارسال پیامک.');
                }

                // ذخیره batch_id برای اولین دسته (یا می‌تونید همه رو ذخیره کنید)
                if (!$this->groupSmsRequest->batch_id) {
                    $this->groupSmsRequest->update(['batch_id' => $result['batch_id'] ?? 'unknown']);
                }

                Log::info('Group SMS batch sent successfully', [
                    'request_id' => $this->groupSmsRequest->id,
                    'batch_id' => $result['batch_id'] ?? 'unknown',
                    'numbers_count' => count($chunk),
                ]);
            } catch (\Exception $e) {
                Log::error('Error sending group SMS batch', [
                    'request_id' => $this->groupSmsRequest->id,
                    'error' => $e->getMessage(),
                    'numbers_count' => count($chunk),
                ]);
                // در صورت خطا، می‌تونید تصمیم بگیرید که ادامه بدید یا متوقف کنید
            }
        }
    }
}