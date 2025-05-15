<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت')</title>

    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
            padding-top: 56px; /* ارتفاع navbar */
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030; /* بالاتر از همه المان‌ها */
            height: 56px;
        }
        .navbar .container-fluid {
            padding: 0 15px;
        }
        .navbar-collapse {
            display: flex !important; /* مطمئن شیم تو موبایل مخفی نمی‌شه */
        }
        .sidebar {
            height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            right: 0;
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .content {
            margin-right: 250px;
            padding: 20px;
            min-height: calc(100vh - 56px);
        }
        /* تنظیمات رسپانسیو */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(100%);
            }
            .content {
                margin-right: 0;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            /* navbar تو موبایل همیشه نمایش داده بشه */
            .navbar {
                display: block !important;
            }
            .navbar-collapse {
                display: none !important; /* منوها تو موبایل پشت همبرگری مخفی بمونن */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        @include('layouts.partials.navbar')
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 content">
                @yield('content')
            </div>
            <div class="col-md-3 sidebar" id="sidebar">
                @include('layouts.partials.sidebar')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // نمایش/مخفی کردن سایدبار تو موبایل
        document.getElementById('navbarToggler').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>