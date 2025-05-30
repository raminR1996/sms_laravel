<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="سامانه پیامکی پیام فردا، ارائه‌دهنده خدمات ارسال پیامک انبوه و خدماتی">
    <meta name="keywords" content="پیامک انبوه, سامانه پیامکی, پیام فردا, ارسال پیامک تبلیغاتی">
    <meta name="robots" content="index, follow">
    <title>{{ settings('site_title', 'پیام فردا') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" loading="lazy">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" media="print" onload="this.media='all'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta http-equiv="Cache-Control" content="public, max-age=31536000">
    @yield('css') <!-- برای لود استایل‌های اضافی -->
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="پیام فردا" width="150" loading="lazy">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">خانه</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/services') }}">خدمات</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">تعرفه‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/blog') }}">وبلاگ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">تماس با ما</a></li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="btn btn-primary btn-sm ms-3" href="{{ url('otp-login') }}">ورود/ثبت‌نام</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

<!-- Footer -->
<footer class="footer py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- لوگو و توضیحات -->
            <div class="col-12 col-md-4 text-center text-md-end">
                <a href="{{ url('/') }}" class="footer-brand d-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="پیام فردا" width="120" loading="lazy">
                </a>
                <p class="text-muted">ارائه‌دهنده خدمات پیامکی با کیفیت و مطمئن.</p>
            </div>

            <!-- لینک‌های مفید -->
            <div class="col-12 col-md-4 text-center text-md-end">
                <h5 class="footer-title">لینک‌های مفید</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/about') }}"><i class="fas fa-info-circle"></i> درباره ما</a></li>
                    <li><a href="{{ url('/services') }}"><i class="fas fa-concierge-bell"></i> خدمات</a></li>
                    <li><a href="{{ url('/contact') }}"><i class="fas fa-envelope"></i> تماس با ما</a></li>
                </ul>
            </div>

            <!-- تماس با ما -->
            <div class="col-12 col-md-4 text-center text-md-end">
                <h5 class="footer-title">تماس با ما</h5>
                <ul class="list-unstyled footer-contact">
                    <li><i class="fas fa-envelope"></i> ایمیل: <a href="mailto:info@payamfarda.ir">info@payamfarda.ir</a></li>
                    <li><i class="fas fa-phone-alt"></i> تلفن: 021-12345678</li>
                    <li><i class="fas fa-map-marker-alt"></i> آدرس: تهران، خیابان اصلی، پلاک ۱۲۳</li>
                </ul>
                <a href="{{ url('/contact') }}" class="btn btn-primary mt-3">تماس بگیرید</a>
            </div>
        </div>

        <hr class="my-4">

        <!-- کپی‌رایت -->
        <div class="text-center">
            <p class="mb-0 text-muted">© {{ now()->year }} {{ settings('site_title', 'پیام فردا') }}. تمامی حقوق محفوظ است.</p>
        </div>
    </div>
</footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- اضافه کردن jQuery -->
    <script async defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js') <!-- برای لود اسکریپت‌های اضافی -->
</body>
</html>