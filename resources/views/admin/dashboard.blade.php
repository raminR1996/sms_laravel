@extends('layouts.layout')

@section('title', $siteTitle . ' - داشبورد مدیریت')
@section('content')
    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">{{ $siteTitle }} - داشبورد مدیریت</h1>
            <div class="card-grid">
                <div class="card">
                    <h5 class="card-title">ارسال پیامک</h5>
                    <p class="card-text">ارسال پیامک تکی یا انبوه به مخاطبین.</p>
                    <a href="#" class="card-btn">شروع کنید</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت کمپین‌ها</h5>
                    <p class="card-text">ایجاد، برنامه‌ریزی و مشاهده کمپین‌ها.</p>
                    <a href="#" class="card-btn">مشاهده کمپین‌ها</a>
                </div>
                <div class="card">
                    <h5 class="card-title">گزارش‌ها</h5>
                    <p class="card-text">تحلیل ارسال‌ها، تحویل و نرخ کلیک‌ها.</p>
                    <a href="#" class="card-btn">نمایش گزارش‌ها</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت مخاطبین</h5>
                    <p class="card-text">افزودن، دسته‌بندی و ویرایش مخاطبین.</p>
                    <a href="#" class="card-btn">مدیریت مخاطبین</a>
                </div>
                <div class="card">
                    <h5 class="card-title">تنظیمات</h5>
                    <p class="card-text">مدیریت اعلان‌ها، شماره‌ها و زمان‌بندی.</p>
                    <a href="{{route('admin.settings.index')}}" class="card-btn">تنظیمات</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت کاربران</h5>
                    <p class="card-text">ایجاد و مدیریت کاربران سیستم.</p>
                    <a href="#" class="card-btn">مدیریت کاربران</a>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="card-text">خوش آمدید به داشبورد مدیریت!</p>
                </div>
            </div>
        </div>
    </div>
@endsection