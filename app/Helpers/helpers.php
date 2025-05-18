<?php

if (!function_exists('settings')) {
    /**
     * دسترسی آسان به تنظیمات کش شده
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
  function settings($key = null, $default = null)
{
    $settings = app(\App\Services\SettingService::class)->getSettings();

    if ($key === null) {
        return $settings;
    }

    return $settings[$key] ?? $default;
}
}