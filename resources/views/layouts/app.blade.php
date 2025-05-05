<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
    </style>
</head>
<body class="bg-light">
    @include('layouts.navigation')

    <main class="container my-4">
        @isset($header)
            <div class="bg-white shadow p-3 mb-4 rounded">
                {{ $header }}
            </div>
        @endisset
    
        @yield('content')
    </main>
    

</body>
</html>
