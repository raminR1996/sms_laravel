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

    UpdateGroupSmsStatusJob::dispatch($report)->onQueue('sms-status');
    return back()->with('success', 'درخواست به‌روزرسانی وضعیت در صف قرار گرفت.');
}
}