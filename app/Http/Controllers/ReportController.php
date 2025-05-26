<?php
namespace App\Http\Controllers;

use App\Models\SmsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    /**
     * نمایش صفحه گزارشات.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.sms.reports.index');
    }

    /**
     * لود داده‌های گزارشات برای Datatables با AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        $reports = SmsReport::where('user_id', auth()->id())->latest()->get();

        return DataTables::of($reports)
            ->addIndexColumn()
            ->addColumn('created_at', function ($report) {
                return \Morilog\Jalali\Jalalian::fromCarbon($report->created_at)->format('Y-m-d H:i');
            })
            ->addColumn('type', function ($report) {
                return $report->type == 'single' ? 'تکی' : 'گروهی';
            })
            ->addColumn('line_number', function ($report) {
                return $report->line_number;
            })
            ->addColumn('numbers_count', function ($report) {
                return is_array($report->numbers) ? count($report->numbers) : 'نامعتبر';
            })
            ->addColumn('message', function ($report) {
                return Str::limit($report->message, 50);
            })
            ->addColumn('sms_count', function ($report) {
                return $report->sms_count;
            })
            ->addColumn('action', function ($report) {
                return view('user.sms.reports.actions', compact('report'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // متد updateStatus بدون تغییر باقی می‌مونه
    public function updateStatus($id)
    {
        $report = SmsReport::where('user_id', auth()->id())->findOrFail($id);

        try {
            $apiKey = env('SABANOVIN_API_KEY');
            $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/status.json";

            $client = new \GuzzleHttp\Client();
            $response = $client->get($url, [
                'query' => [
                    'batch_id' => $report->batch_id,
                ],
                'headers' => [
                    'Jalali-DateTime' => '1',
                ],
            ]);

            Log::info('SMS status request sent', [
                'report_id' => $report->id,
                'batch_id'  => $report->batch_id,
                'url'       => $url,
            ]);

            $result = json_decode($response->getBody(), true);

            if (is_null($result)) {
                Log::error('Invalid JSON response from Sabanovin', [
                    'report_id' => $report->id,
                    'body'      => $response->getBody()->getContents(),
                ]);
                return back()->with('error', 'خطا در دریافت وضعیت: پاسخ سرور نامعتبر است.');
            }

            if (isset($result['status']['code']) && $result['status']['code'] == 200) {
                Log::info('SMS status retrieved successfully', [
                    'report_id' => $report->id,
                    'response'  => $result,
                ]);

                $statuses = [];
                $datetimes = [];
                if (isset($result['entries']) && is_array($result['entries'])) {
                    foreach ($report->numbers as $index => $number) {
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

                $report->statuses = $statuses;
                $report->datetimes = $datetimes;
                $report->save();

                return back()->with('success', 'وضعیت گزارش به‌روز شد.');
            }

            Log::error('Failed to retrieve SMS status', [
                'report_id' => $report->id,
                'status'    => $result['status'] ?? 'unknown',
                'body'      => $response->getBody()->getContents(),
                'headers'   => $response->getHeaders(),
                'result'    => $result,
            ]);

            $errorMessage = $result['status']['message'] ?? 'خطای ناشناخته (کد: ' . ($result['status']['code'] ?? 'نامشخص') . ')';

            return back()->with('error', 'خطا در دریافت وضعیت: ' . $errorMessage);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request exception while retrieving SMS status', [
                'report_id' => $report->id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
                'response'  => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);

            return back()->with('error', 'خطا در دریافت وضعیت: ' . ($e->getMessage() ?? 'مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.'));
        } catch (\Exception $e) {
            Log::error('Exception while retrieving SMS status', [
                'report_id' => $report->id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'خطا در دریافت وضعیت: مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.');
        }
    }
}