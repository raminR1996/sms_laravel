<?php

if (! function_exists('settings')) {
    /**
     * دسترسی آسان به تنظیمات کش شده
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        $setting = app(\App\Services\SettingService::class)->getSettings();

        if (!$setting) {
            return $default ?? null;
        }

        if ($key === null) {
            return $setting;
        }

        return $setting->$key ?? $default;
    }
}

