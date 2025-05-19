@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center text-white py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider1.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 class="display-3 mb-4 animate__animated animate__fadeIn">سامانه پیامکی حرفه‌ای {{ settings('site_title', 'پیام فردا') }}</h1>
        <p class="lead mb-4 animate__animated animate__fadeIn animate__delay-1s">ارسال پیامک انبوه، خدماتی و تبلیغاتی با سرعت و اطمینان</p>
        <a href="{{ url('/register') }}" class="btn btn-primary  btn-lg px-5 animate__animated animate__fadeIn animate__delay-2s">همین حالا شروع کنید</a>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">خدمات پیامکی ما</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-send-fill" style="font-size: 3rem; color: #007bff;"></i>
                        <h5 class="card-title mt-3">پیامک انبوه</h5>
                        <p class="card-text">ارسال پیامک به هزاران نفر در کسری از ثانیه.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-shield-check-fill" style="font-size: 3rem; color: #007bff;"></i>
                        <h5 class="card-title mt-3">خطوط خدماتی</h5>
                        <p class="card-text">ارسال پیامک به لیست سیاه با خطوط خدماتی.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-code-slash" style="font-size: 3rem; color: #007bff;"></i>
                        <h5 class="card-title mt-3">وب‌سرویس API</h5>
                        <p class="card-text">اتصال آسان به سامانه با API پیشرفته.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pricing-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">تعرفه‌های ما</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card border-0 shadow-sm text-center h-100 animate__animated animate__fadeIn">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">پایه</h5>
                    </div>
                    <div class="card-body text-center"> <!-- اضافه کردن text-center به card-body -->
                        <h3 class="card-title pricing-price">۵۰,۰۰۰ تومان</h3>
                        <p class="card-text">مناسب برای کسب‌وکارهای کوچک</p>
                        <ul class="list-unstyled pricing-features">
                            <li>۵,۰۰۰ پیامک</li>
                            <li>پشتیبانی ایمیلی</li>
                            <li>خط اشتراکی</li>
                        </ul>
                        <a href="{{ url('/register') }}" class="btn btn-outline-primary mt-3">انتخاب</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card border-0 shadow-sm text-center h-100 position-relative animate__animated animate__fadeIn animate__delay-1s">
                    <span class="badge bg-success position-absolute top-0 start-50 translate-middle-x">محبوب‌ترین</span>
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">حرفه‌ای</h5>
                    </div>
                    <div class="card-body text-center"> <!-- اضافه کردن text-center به card-body -->
                        <h3 class="card-title pricing-price">۱۵۰,۰۰۰ تومان</h3>
                        <p class="card-text">مناسب برای کسب‌وکارهای متوسط</p>
                        <ul class="list-unstyled pricing-features">
                            <li>۲۰,۰۰۰ پیامک</li>
                            <li>پشتیبانی تلفنی و ایمیلی</li>
                            <li>خط اختصاصی</li>
                        </ul>
                        <a href="{{ url('/register') }}" class="btn btn-primary mt-3">انتخاب</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection