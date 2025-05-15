@extends('layouts.layout')

@section('title', $siteTitle . ' - داشبورد کاربر')
@section('content')
    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">{{ $siteTitle }} - داشبورد کاربر</h1>
            <div class="card-grid">
                <div class="card">
                    <h5 class="card-title">ارسال پیامک</h5>
                    <p class="card-text">ارسال پیامک تکی یا انبوه به مخاطبین.</p>
                    <a href="{{ route('send.sms') }}" class="card-btn">شروع کنید</a>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="card-text">خوش آمدید به داشبورد کاربر!</p>
                </div>
            </div>
        </div>
    </div>
@endsection