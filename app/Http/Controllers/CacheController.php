<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clearCache()
    {
        // اجرای دستورات Artisan برای پاک کردن کش
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        // بازگشت پیام موفقیت
        return response()->json([
            'message' => 'Cache cleared successfully!'
        ], 200);
    }
}