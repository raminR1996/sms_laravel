```html
@extends('layouts.layout')

@section('title', $siteTitle . ' - داشبورد کاربر')
@section('content')
    <div class="dashboard-page">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <h1 class="text-center mb-4">{{ $siteTitle }} - داشبورد کاربر</h1>
            <div class="card-grid">
                <div class="card">
                    <h5 class="card-title">ارسال پیامک</h5>
                    <p class="card-text">ارسال پیامک تکی یا انبوه به مخاطبین.</p>
                    <a href="{{ route('send.sms') }}" class="card-btn">شروع کنید</a>
                </div>
                @if (auth()->user()->profile_completed && !auth()->user()->documents && !auth()->user()->documents_verified)
                    <div class="card">
                        <h5 class="card-title">آپلود مدارک</h5>
                        <p class="card-text">مدارک شما رد شده است. لطفاً مدارک جدیدی آپلود کنید.</p>
                        <a href="{{ route('documents.upload.form') }}" class="card-btn">آپلود مدارک</a>
                    </div>
                @endif
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="card-text">خوش آمدید به داشبورد کاربر!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
```