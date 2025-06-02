<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): View
    {
        return view('admin.settings.index');
    }

    // متد جدید برای دریافت داده‌های جدول با Ajax
    public function getData()
    {
        $settings = Setting::query();

        return DataTables::of($settings)
            ->addIndexColumn()
            ->editColumn('value', function ($setting) {
                return \Str::limit($setting->value, 50);
            })
            ->addColumn('actions', function ($setting) {
                return view('admin.settings.actions', compact('setting'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'nullable|string',
        ]);

        $this->settingService->createSetting($validated);

        return redirect()->route('admin.settings.index')->with('success', 'تنظیمات با موفقیت ایجاد شد.');
    }

    public function edit($id): View
    {
        $setting = Setting::findOrFail($id);
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key,' . $id,
            'value' => 'nullable|string',
        ]);

        $this->settingService->updateSetting($id, $validated);

        return redirect()->route('admin.settings.index')->with('success', 'تنظیمات با موفقیت به‌روزرسانی شد.');
    }

    public function destroy($id)
    {
        $this->settingService->deleteSetting($id);
        return redirect()->route('admin.settings.index')->with('success', 'تنظیمات با موفقیت حذف شد.');
    }
}