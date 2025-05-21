@extends('layouts.layout')
@section('css')
  <!-- فایل CSS -->
    <link rel="stylesheet" href="{{ asset('css/profile-page.css') }}">
@endsection
@section('content')
    <div class="profile-page">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h4 class="mb-0">تکمیل پروفایل</h4>
                            <p class="mb-0">لطفاً اطلاعات خود را وارد کنید</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.complete.store') }}">
                                @csrf

                                <!-- نام کامل -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">نام کامل</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control has-icon @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ایمیل -->
                                <div class="mb-4">
                                    <label for="email" class="form-label">ایمیل</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control has-icon @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- شماره تلفن -->
                                <div class="mb-4">
                                    <label for="phone" class="form-label">شماره تماس</label>
                                    <input type="text" class="form-control" id="phone" value="{{ auth()->user()->phone_number }}" disabled>
                                </div>

                                <!-- دکمه ارسال -->
                                <button type="submit" class="btn btn-success w-100 py-2">ثبت و ادامه</button>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <p class="text-muted mb-0">اگر قبلاً اطلاعات خود را وارد کرده‌اید، می‌توانید وارد حساب خود شوید.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection