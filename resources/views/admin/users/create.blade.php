@extends('layouts.layout')

@section('title', 'ایجاد کاربر جدید')

@section('content')
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0">ایجاد کاربر جدید</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label">نام کامل</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control has-icon @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">ایمیل</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control has-icon @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="phone_number" class="form-label">شماره تماس</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control has-icon @error('phone_number') is-invalid @enderror"
                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label">نقش</label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="" disabled selected>انتخاب نقش</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ادمین</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>کارمند</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>کاربر</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">رمز عبور</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control has-icon @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control has-icon" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2">ایجاد کاربر</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection