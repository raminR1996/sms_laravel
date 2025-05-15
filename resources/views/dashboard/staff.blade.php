@extends('layouts.layout')

@section('title', $siteTitle . ' - داشبورد کارمند')
@section('content')
    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">{{ $siteTitle }} - داشبورد کارمند</h1>
            <div class="card-grid">
                <div class="card">
                    <h5 class="card-title">ارسال پیامک</h5>
                    <p class="card-text">ارسال پیامک تکی یا انبوه به مخاطبین.</p>
                    <a href="{{ route('send.sms') }}" class="card-btn">شروع کنید</a>
                </div>
                <div class="card">
                    <h5 class="card-title">مدیریت مخاطبین</h5>
                    <p class="card-text">افزودن، دسته‌بندی و ویرایش مخاطبین.</p>
                    <a href="{{ route('contacts.index') }}" class="card-btn">مدیریت مخاطبین</a>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="card-text">خوش آمدید به داشبورد کارمند!</p>
                </div>
            </div>
        </div>
    </div>
@endsection