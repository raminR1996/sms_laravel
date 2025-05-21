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
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4 text-center text-md-right">
                    <h5>{{ settings('site_title', 'پیام فردا') }}</h5>
                    <p>ارائه‌دهنده خدمات پیامکی با کیفیت و مطمئن.</p>
                </div>
                <div class="col-12 col-md-4 text-center text-md-right">
                    <h5>لینک‌های مفید</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/about') }}" class="text-white">درباره ما</a></li>
                        <li><a href="{{ url('/services') }}" class="text-white">خدمات</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-white">تماس با ما</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 text-center text-md-right">
                    <h5>تماس با ما</h5>
                    <p>ایمیل: info@payamfarda.ir<br>تلفن: 021-12345678<br>آدرس: تهران، خیابان اصلی، پلاک ۱۲۳</p>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-light mt-3">تماس بگیرید</a>
                </div>
            </div>
            <hr class="bg-white my-4">
            <p class="text-center mb-0">© {{ now()->year }} {{ settings('site_title', 'پیام فردا') }}. تمامی حقوق محفوظ است.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- اضافه کردن jQuery -->
    <script async defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js') <!-- برای لود اسکریپت‌های اضافی -->
</body>
</html>