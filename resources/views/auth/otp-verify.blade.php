@extends('layouts.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm mt-5">
                <div class="card-body">
                    <h3 class="text-center mb-4">تأیید کد ارسال‌شده</h3>

                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">کد تایید</label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   placeholder="مثلاً 123456"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">تأیید</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted small">کدی دریافت نکردید؟ <a href="#">ارسال مجدد کد</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
