@extends('layouts.layout')

@section('title', 'پنل کاربری')
@section('content')
    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">میز کار</h1>
            <div class="card-grid">
                <!-- کارت ارسال پیامک -->
                <div class="card">
                    <h5 class="card-title">ارسال پیامک</h5>
                    <p class="card-text">ارسال پیامک تکی یا انبوه به مخاطبین خود.</p>
                    <a href="#" class="card-btn">شروع کنید</a>
                </div>
                <!-- کارت مدیریت کمپین‌ها -->
                <div class="card">
                    <h5 class="card-title">مدیریت کمپین‌ها</h5>
                    <p class="card-text">ایجاد، برنامه‌ریزی و مشاهده کمپین‌های پیامکی.</p>
                     <a href="#" class="card-btn"> مشاهده کمپین‌ها </a>
                </div>
                <!-- کارت گزارش‌ها -->
                <div class="card">
                    <h5 class="card-title">گزارش‌ها</h5>
                    <p class="card-text">تحلیل ارسال‌ها، تحویل و نرخ کلیک‌ها.</p>
                    <a href="#" class="card-btn">نمایش گزارش‌ها</a>
                </div>
                <!-- کارت مدیریت مخاطبین -->
                <div class="card">
                    <h5 class="card-title">مدیریت مخاطبین</h5>
                    <p class="card-text">افزودن، دسته‌بندی و ویرایش مخاطبین.</p>
                    <a href="#" class="card-btn">مدیریت مخاطبین</a>
                </div>
                <!-- کارت تنظیمات -->
                <div class="card">
                    <h5 class="card-title">تنظیمات</h5>
                    <p class="card-text">مدیریت اعلان‌ها، شماره‌ها و زمان‌بندی.</p>
                    <a href="#" class="card-btn">تنظیمات</a>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="card-text">خوش آمدید به پنل کاربری!</p>
                </div>
            </div>
        </div>
    </div>
@endsection