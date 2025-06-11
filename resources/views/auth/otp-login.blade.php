@extends('layouts.auth')

@section('title', 'ورود به پنل')

@section('content')
    <h3>ورود به پنل</h3>
    <p class="subtitle">OTP ارسال</p>

    <form method="POST" action="{{ route('otp.send') }}">
        @csrf
        <div class="mb-4">
            <label for="phone_number" class="form-label">شماره همراه</label>
            <input type="text"
                   class="form-control @error('phone_number') is-invalid @enderror"
                   id="phone_number"
                   name="phone_number"
                   placeholder="09123456789"
                   required>
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">مرا به خاطر بسپار</label>
        </div>

        <button type="submit" class="btn btn-primary">ورود</button>
    </form>

    <p class="signup-link">
        حساب کاربری ندارید؟ <a href="#">ثبت‌نام</a>
    </p>
@endsection