@extends('layouts.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm mt-5">
                <div class="card-body">
                    <h3 class="text-center mb-4">ورود با شماره موبایل</h3>

                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('otp.send') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">شماره موبایل</label>
                            <input type="text"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   id="phone_number"
                                   name="phone_number"
                                   placeholder="مثلاً 09123456789"
                                   required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">ارسال کد تایید</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>حساب کاربری دارید؟ <a href="/login">ورود با رمز عبور</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
