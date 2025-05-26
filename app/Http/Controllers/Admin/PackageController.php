<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        // دریافت sms_price از تنظیمات
        $smsPrice = Setting::where('key', 'sms_price')->first()->value ?? 100;

        // به‌روزرسانی قیمت تمام بسته‌ها
        $packages = Package::all();
        foreach ($packages as $package) {
            $package->update([
                'price' => $package->sms_count * $smsPrice,
            ]);
        }

        // دریافت لیست بسته‌ها برای نمایش
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sms_count' => 'required|integer|in:100,500,1000,5000,10000,50000',
            'is_active' => 'boolean',
        ]);

        $smsPrice = Setting::where('key', 'sms_price')->first()->value ?? 100;
        $price = $request->sms_count * $smsPrice;

        Package::create([
            'sms_count' => $request->sms_count,
            'price' => $price,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'بسته با موفقیت ایجاد شد.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'sms_count' => 'required|integer|in:100,500,1000,5000,10000,50000',
            'is_active' => 'boolean',
        ]);

        $smsPrice = Setting::where('key', 'sms_price')->first()->value ?? 100;
        $price = $request->sms_count * $smsPrice;

        $package->update([
            'sms_count' => $request->sms_count,
            'price' => $price,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'بسته با موفقیت ویرایش شد.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'بسته با موفقیت حذف شد.');
    }

    public function toggleStatus(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        return redirect()->route('admin.packages.index')->with('success', 'وضعیت بسته تغییر کرد.');
    }
}