<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected $cacheKey = 'settings_cache';

    public function getSettings($key = null)
    {
        $settings = Cache::remember($this->cacheKey, 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return $key ? ($settings[$key] ?? null) : $settings;
    }

    public function getAllSettings()
    {
        return Setting::all();
    }

    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }

    public function createSetting(array $data)
    {
        $setting = Setting::create($data);
        $this->clearCache();
        return $setting;
    }

    public function updateSetting($id, array $data)
    {
        $setting = Setting::findOrFail($id);
        $setting->update($data);
        $this->clearCache();
        return $setting;
    }

    public function deleteSetting($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        $this->clearCache();
    }
}