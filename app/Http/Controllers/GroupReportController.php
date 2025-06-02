<?php

namespace App\Http\Controllers;

use App\Models\GroupSmsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class GroupReportController extends Controller
{
    /**
     * نمایش صفحه گزارشات گروهی.
     */
    public function index()
    {
        return view('user.sms.reports.group');
    }

    /**
     * لود داده‌های گزارشات گروهی برای Datatables با AJAX.
     */
    public function getData()
    {
        $reports = GroupSmsRequest::where('user_id', auth()->id())->latest()->get();

        return DataTables::of($reports)
            ->addIndexColumn()
            ->addColumn('created_at', function ($report) {
                return \Morilog\Jalali\Jalalian::fromCarbon($report->created_at)->format('Y-m-d H:i');
            })
            ->addColumn('line_number', function ($report) {
                return $report->line_number;
            })
            ->addColumn('numbers_count', function ($report) {
                $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids)
                    ->pluck('mobile_number')
                    ->toArray();
                return count($numbers);
            })
            ->addColumn('villages', function ($report) {
                $villages = \App\Models\Village::whereIn('id', $report->village_ids)->pluck('name')->toArray();
                return implode(', ', $villages);
            })
            ->addColumn('message', function ($report) {
                return Str::limit($report->message, 50);
            })
            ->addColumn('sms_count', function ($report) {
                return $report->sms_count;
            })
            ->addColumn('status', function ($report) {
                if ($report->status === 'pending') {
                    return '<span class="badge bg-warning">در انتظار</span>';
                } elseif ($report->status === 'approved') {
                    return '<span class="badge bg-success">تأیید شده</span>';
                } elseif ($report->status === 'rejected') {
                    return '<span class="badge bg-danger">رد شده</span>';
                }
                return '-';
            })
            ->addColumn('action', function ($report) {
                $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids)
                    ->pluck('mobile_number')
                    ->toArray();
                return view('user.sms.reports.group-actions', compact('report', 'numbers'))->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * به‌روزرسانی وضعیت گزارش گروهی.
     */
public function updateStatus($id)
{
    $report = GroupSmsRequest::where('user_id', auth()->id())->findOrFail($id);

    if ($report->status !== 'approved') {
        return back()->with('error', 'فقط گزارش‌های تأییدشده می‌توانند به‌روزرسانی شوند.');
    }

    try {
        $apiKey = env('SABANOVIN_API_KEY');
        $url = "https://api.sabanovin.com/v1/{$apiKey}/sms/status.json";
        $client = new \GuzzleHttp\Client();

        $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids)
            ->pluck('mobile_number')
            ->toArray();
        $statuses = [];
        $datetimes = [];

        // بررسی batch_idها
        $batchIds = is_array($report->batch_id) ? $report->batch_id : (is_string($report->batch_id) ? json_decode($report->batch_id, true) : [$report->batch_id]);
        if (empty($batchIds)) {
            return back()->with('error', 'شناسه دسته (batch_id) یافت نشد.');
        }

        foreach ($batchIds as $batchId) {
            // اطمینان از اینکه batch_id یک رشته است
            $batchId = is_string($batchId) ? $batchId : (string)$batchId;

            $response = $client->get($url, [
                'query' => ['batch_id' => $batchId],
                'headers' => ['Jalali-DateTime' => '1'],
            ]);

            $body = $response->getBody()->getContents();
            $result = json_decode($body, true);

            if (is_null($result)) {
                Log::error('Invalid JSON response from Sabanovin', [
                    'report_id' => $report->id,
                    'batch_id' => $batchId,
                    'body' => $body,
                ]);
                continue; // ادامه با batch_id بعدی
            }

            if (isset($result['status']['code']) && $result['status']['code'] == 200) {
                Log::info('SMS status retrieved successfully', [
                    'report_id' => $report->id,
                    'batch_id' => $batchId,
                    'response' => $result,
                ]);

                if (isset($result['entries']) && is_array($result['entries'])) {
                    foreach ($numbers as $index => $number) {
                        foreach ($result['entries'] as $entry) {
                            if (isset($entry['number']) && $entry['number'] == $number) {
                                $statuses[$index] = $entry['status'] ?? 'unknown';
                                $datetimes[$index] = $entry['datetime'] ?? null;
                                break;
                            }
                        }
                    }
                }
            } else {
                Log::error('Failed to retrieve SMS status', [
                    'report_id' => $report->id,
                    'batch_id' => $batchId,
                    'status' => $result['status'] ?? 'unknown',
                    'body' => $body,
                ]);
            }
        }

        if (!empty($statuses)) {
            $report->statuses = $statuses;
            $report->datetimes = $datetimes;
            $report->save();
            return back()->with('success', 'وضعیت گزارش به‌روز شد.');
        }

        return back()->with('error', 'هیچ وضعیتی دریافت نشد.');
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        Log::error('Request exception while retrieving SMS status', [
            'report_id' => $report->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
        ]);
        return back()->with('error', 'خطا در دریافت وضعیت: ' . ($e->getMessage() ?? 'مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.'));
    } catch (\Exception $e) {
        Log::error('Exception while retrieving SMS status', [
            'report_id' => $report->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->with('error', 'خطا در دریافت وضعیت: مشکل در اتصال به سرور. لطفاً دوباره تلاش کنید.');
    }
}
}