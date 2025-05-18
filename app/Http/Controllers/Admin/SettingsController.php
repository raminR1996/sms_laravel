<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function edit()
    {
        $settings = $this->settingService->getSettings();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'default_sms_number' => 'nullable|string|max:20',
        ]);

        $this->settingService->updateSettings($validated);

        return redirect()->back()->with('success', 'تنظیمات با موفقیت بروزرسانی شد.');
    }

}
