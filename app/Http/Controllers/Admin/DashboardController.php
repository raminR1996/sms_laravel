<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Setting;

class DashboardController extends Controller
{

  public function index()
    {
        $siteTitle = settings('site_title') ?? 'پنل کاربری';
        return view('admin.dashboard', compact('siteTitle'));
    }
}
