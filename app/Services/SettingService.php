<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected $cacheKey = 'settings_cache';

    public function getSettings()
    {
        return Cache::remember($this->cacheKey, 3600, function () {
            return Setting::first();
        });
    }

    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }

    public function updateSettings(array $data)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create($data);
        } else {
            $setting->update($data);
        }
        $this->clearCache();
        return $setting;
    }
}
