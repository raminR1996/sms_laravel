<div class="container-fluid d-flex align-items-center">
    <!-- لوگو -->
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="پیام فردا" width="150" loading="lazy">
    </a>

    <!-- نمایش مانده اعتبار فقط برای کاربران با نقش user -->
    @if(auth()->check() && auth()->user()->role === 'user')
        <span class="d-none d-md-flex text-white bg-info rounded-pill px-3 py-1 mx-2">
            <i class="fas fa-sms me-2"></i> مانده پیامک: {{ auth()->user()->sms_balance }} عدد
        </span>
        <!-- نمایش کوتاه‌تر در موبایل -->
        <span class="d-flex d-md-none text-white bg-info rounded-pill px-2 py-1 mx-2">
            <i class="fas fa-sms me-1"></i> مانده  :  {{ auth()->user()->sms_balance }}
        </span>
    @endif

    <!-- دکمه برای باز و بسته کردن سایدبار در موبایل -->
    <button class="navbar-toggler d-lg-none ms-auto" type="button" id="navbarToggler" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
            <!-- دکمه خروج -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> خروج
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>