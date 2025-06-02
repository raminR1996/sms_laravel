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
    $chunks = array_chunk($this->numbers, 100);
    $batchIds = [];
    $failed = false;

    foreach ($chunks as $index => $chunk) {
        try {
            if ($index > 0) {
                usleep(600000);
            }

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

            $batchIds[] = $result['batch_id'] ?? 'unknown';
        } catch (\Exception $e) {
            Log::error('Error sending group SMS batch', [
                'request_id' => $this->groupSmsRequest->id,
                'error' => $e->getMessage(),
                'numbers_count' => count($chunk),
            ]);
            $failed = true;
        }
    }

    $this->groupSmsRequest->update([
        'batch_id' => json_encode($batchIds),
        'status' => $failed ? 'failed' : 'approved',
    ]);
}
}