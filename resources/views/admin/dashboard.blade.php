@extends('layouts.layout')

@section('title', $siteTitle . ' - داشبورد مدیریت')
@section('content')

    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">{{ $siteTitle }} - داشبورد مدیریت</h1>
            <div class="card-grid">
                 <div class="card">
                    <h5 class="card-title">مدیریت خطوط</h5>
                    <p class="card-text">مدیریت خطوط پیامکی اپراتورها .</p>
                    <a href="{{ route('admin.lines.index') }}" class="card-btn">مدیریت خطوط</a>
                </div>
                <div class="card">
    <h5 class="card-title">مدیریت بسته‌های شارژ</h5>
    <p class="card-text">ایجاد و مدیریت بسته‌های شارژ پیامک.</p>
    <a href="{{ route('admin.packages.index') }}" class="card-btn">مدیریت بسته‌ها</a>
</div>
                 <div class="card">
                    <h5 class="card-title">مدیریت درخواست‌های پیامک گروهی</h5>
                    <p class="card-text">تأیید یا رد درخواست‌های پیامک گروهی.</p>
                    <a href="{{ route('admin.group-sms-requests') }}" class="card-btn">مدیریت درخواست‌ها</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت مخاطبین</h5>
                    <p class="card-text">افزودن، دسته‌بندی و ویرایش مخاطبین.</p>
                    <a href="{{ route('admin.contacts.index') }}" class="card-btn">مدیریت مخاطبین</a>
                </div>
                <div class="card">
                    <h5 class="card-title">تنظیمات</h5>
                    <p class="card-text">مدیریت اعلان‌ها، شماره‌ها و زمان‌بندی.</p>
                    <a href="{{route('admin.settings.index')}}" class="card-btn">تنظیمات</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت کاربران</h5>
                    <p class="card-text">ایجاد و مدیریت کاربران سیستم.</p>
                    <a href="{{ route('admin.users.index') }}" class="card-btn">مدیریت کاربران</a>
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