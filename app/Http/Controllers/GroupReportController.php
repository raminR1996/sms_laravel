<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateGroupSmsStatusJob;
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
     * لود داده‌های گزارشات گروهی برای DataTables با AJAX.
     */
    public function getData()
    {
        $reports = GroupSmsRequest::where('user_id', auth()->id())->latest();

        return DataTables::of($reports)
            ->addIndexColumn()
            ->editColumn('created_at', function ($report) {
                return \Morilog\Jalali\Jalalian::fromCarbon($report->created_at)->format('Y-m-d H:i') ?? 'نامشخص';
            })
            ->editColumn('line_number', function ($report) {
                return $report->line_number ?? 'نامشخص';
            })
            ->addColumn('villages', function ($report) {
                try {
                    $villages = \App\Models\Village::whereIn('id', $report->village_ids ?? [])->pluck('name')->toArray();
                    return implode(', ', $villages) ?: 'نامشخص';
                } catch (\Exception $e) {
                    Log::error('Error fetching villages for report', [
                        'report_id' => $report->id,
                        'error' => $e->getMessage(),
                    ]);
                    return 'نامشخص';
                }
            })
            ->addColumn('numbers_count', function ($report) {
                try {
                    $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids ?? [])
                        ->pluck('mobile_number')
                        ->toArray();
                    return count($numbers) ?: '0';
                } catch (\Exception $e) {
                    Log::error('Error fetching numbers for report', [
                        'report_id' => $report->id,
                        'error' => $e->getMessage(),
                    ]);
                    return '0';
                }
            })
            ->editColumn('message', function ($report) {
                return Str::limit($report->message, 50) ?? 'نامشخص';
            })
            ->editColumn('sms_count', function ($report) {
                return $report->sms_count ?? '0';
            })
            ->editColumn('status', function ($report) {
                if ($report->status === 'pending') {
                    return '<span class="badge bg-warning">در انتظار</span>';
                } elseif ($report->status === 'approved') {
                    return '<span class="badge bg-success">تأیید شده</span>';
                } elseif ($report->status === 'rejected') {
                    return '<span class="badge bg-danger">رد شده</span>';
                }
                return '<span class="badge bg-secondary">نامشخص</span>';
            })
            ->addColumn('actions', function ($report) {
                try {
                    $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids ?? [])
                        ->pluck('mobile_number')
                        ->toArray();
                    return view('user.sms.reports.group-actions', compact('report', 'numbers'))->render();
                } catch (\Exception $e) {
                    Log::error('Error rendering actions for report', [
                        'report_id' => $report->id,
                        'error' => $e->getMessage(),
                    ]);
                    return '<span class="text-danger">خطا در بارگذاری عملیات</span>';
                }
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * نمایش صفحه جزئیات گزارش گروهی.
     */
    public function details($id)
    {
        $report = GroupSmsRequest::where('user_id', auth()->id())->findOrFail($id);
        return view('user.sms.reports.group-report-details', compact('report'));
    }

    /**
     * لود داده‌های جزئیات گزارش برای DataTables با AJAX.
     */
    public function getDetailsData($id)
    {
        $report = GroupSmsRequest::where('user_id', auth()->id())->findOrFail($id);
        $numbers = \App\Models\Contact::whereIn('village_id', $report->village_ids ?? [])
            ->pluck('mobile_number')
            ->toArray();
        $statuses = $report->statuses ?? [];
        $datetimes = $report->datetimes ?? [];

        $data = [];
        foreach ($numbers as $index => $number) {
            $status = $statuses[$index] ?? 'unknown';
            $statusText = match ($status) {
                'DELIVERED' => 'رسیده',
                'ENQUEUED' => 'در انتظار',
                'FAILED' => 'نرسیده',
                default => 'نامشخص',
            };
            $data[] = [
                'DT_RowIndex' => $index + 1,
                'number' => $number,
                'status' => $statusText,
                'datetime' => $datetimes[$index] ?? 'نامشخص',
            ];
        }

        return DataTables::of($data)->make(true);
    }

    /**
     * به‌روزرسانی وضعیت گزارش گروهی.
     */
    public function updateStatus($id)
    {
        $report = GroupSmsRequest::where('user_id', auth()->id())->findOrFail($id);

        if ($report->status !== 'approved') {
            return response()->json(['error' => 'فقط گزارش‌های تأییدشده می‌توانند به‌روزرسانی شوند.'], 403);
        }

        UpdateGroupSmsStatusJob::dispatch($report)->onQueue('sms-status');
        return response()->json(['success' => 'درخواست به‌روزرسانی وضعیت در صف قرار گرفت.']);
    }
}