@extends('layouts.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm mt-5">
                <div class="card-body">
                    <h3 class="text-center mb-4">ورود به حساب کاربری</h3>
                    <form action="/login" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">ایمیل یا نام کاربری</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">رمز عبور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">ورود</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>حساب کاربری ندارید؟ <a href="/register">ثبت‌نام کنید</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection