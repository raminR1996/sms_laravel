<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): View
    {
        $settings = $this->settingService->getAllSettings();
        return view('admin.settings.index', compact('settings'));
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