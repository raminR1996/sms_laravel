<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
       public function index()
    {
        $siteTitle = Setting::first()->site_title ?? 'پنل کاربری';

        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                  return redirect()->route('admin.dashboard');
            case 'staff':
                return view('dashboard.staff', compact('siteTitle'));
            case 'user':
                return view('dashboard.user', compact('siteTitle'));
            default:
                return redirect()->route('dashboard')->with('error', 'نقش نامعتبر!');
        }
    }

    public function test()
    {
        // دسترسی به تنظیمات 
        $title = settings('site_title');
        $allSettings = settings();
    }
}
