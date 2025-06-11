



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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" rel="stylesheet">

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
@yield('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
    iziToast.settings({
        rtl: true,
        zindex: 99999999999,
        position: 'bottomLeft',
        timeout: 3000,
        progressBar: true,
        closeOnClick: true
    });

    @if (session('success'))
        iziToast.success({
            title: 'موفقیت!',
            message: '{{ session('success') }}'
        });
    @endif

    @if (session('error'))
        iziToast.error({
            title: 'خطا!',
            message: '{{ session('error') }}'
        });
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            iziToast.error({
                title: 'خطا در اعتبارسنجی!',
                message: '{{ $error }}'
            });
        @endforeach
    @endif
</script>
</body>
</html>