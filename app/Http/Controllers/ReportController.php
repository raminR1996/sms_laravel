<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateSingleSmsStatusJob;
use App\Models\SmsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    /**
     * نمایش صفحه گزارشات.
     */
    public function index()
    {
        return view('user.sms.reports.index');
    }

    /**
     * لود داده‌های گزارشات برای Datatables با AJAX.
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

    /**
     * به‌روزرسانی وضعیت گزارش.
     */
public function updateStatus($id)
    {
        $report = SmsReport::where('user_id', auth()->id())->findOrFail($id);
        UpdateSingleSmsStatusJob::dispatch($report)->onQueue('sms-status');
        return back()->with('success', 'درخواست به‌روزرسانی وضعیت در صف قرار گرفت.');
    }
}