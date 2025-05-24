<div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="پیام فردا" width="150" loading="lazy">
    </a>

    <!-- دکمه برای باز و بسته کردن سایدبار در موبایل -->
    <button class="navbar-toggler d-lg-none" type="button" id="navbarToggler" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
           <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">
        <i class="fas fa-sign-out-alt"></i> خروج
    </button>
</form>

        </ul>
    </div>
</div>
