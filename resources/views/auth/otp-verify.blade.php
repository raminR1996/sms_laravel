@extends('layouts.auth')

@section('title', 'تأیید کد')

@section('content')
    <h3>تأیید کد</h3>
    <p class="subtitle">کدی که به شماره {{ session('phone_number') }} ارسال شده را وارد کنید</p>


    <form method="POST" action="{{ route('otp.verify.post') }}">
        @csrf
        <div class="mb-4">
            <label for="code" class="form-label">کد تأیید</label>
            <input type="text"
                   id="code"
                   name="code"
                   class="form-control @error('code') is-invalid @enderror"
                   placeholder="123456"
                   required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">تأیید</button>
    </form>

    <p class="signup-link">
        کدی دریافت نکردید؟
        <form method="POST" action="{{ route('otp.send') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="phone_number" value="{{ session('phone_number') }}">
            <button type="submit" class="btn btn-link p-0" style="color: #4a90e2; text-decoration: none;">ارسال مجدد</button>
        </form>
    </p>
@endsection