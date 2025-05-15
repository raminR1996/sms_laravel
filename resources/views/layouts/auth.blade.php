



<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ورود به سیستم')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css" rel="stylesheet">
    <!-- لود فایل CSS جداگانه -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container-fluid">
        <!-- بخش چپ: تبلیغاتی (آبی) -->
        <div class="left-section">
            <div class="logo">درودزن پیام</div>
            <div class="illustration">
                <img src="{{ asset('images/sms_illustration.png') }}" alt="SMS Illustration">
            </div>
            <p>
                سیستم ارسال پیامک درودزن پیام، راه‌حل سریع و مطمئن برای اطلاع‌رسانی و تبلیغات شما. با ما تجربه‌ای متفاوت داشته باشید!
            </p>
        </div>
        <!-- بخش راست: فرم -->
        <div class="right-section">
            <div class="form-container">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>