<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link active" href="/"><i class="fas fa-home me-2"></i> خانه</a>
    </li>

    <!-- منوهای نقش admin -->
@if(auth()->check() && auth()->user()->role === 'admin')
        <li class="nav-item">
            <details class="sidebar-submenu">
                <summary class="nav-link">
                    <i class="fas fa-users me-2"></i> مدیریت کاربران
                </summary>
                <ul class="submenu-items">
                    <li><a class="nav-link submenu-link" href="{{ route('admin.users.index') }}"><i class="fas fa-user me-2"></i> مدیریت کاربران</a></li>
                    <li><a class="nav-link submenu-link" href="{{ route('admin.verify.documents') }}"><i class="fas fa-id-card me-2"></i> مدارک</a></li>
                    <li><a class="nav-link submenu-link" href="{{ route('admin.contacts.index') }}"><i class="fas fa-address-book me-2"></i> مخاطبین</a></li>
                </ul>
            </details>
        </li>
    @endif

    <!-- منوهای نقش staff -->
  @if(auth()->check() && auth()->user()->role === 'staff')
        <li class="nav-item">
            <details class="sidebar-submenu">
                <summary class="nav-link">
                    <i class="fas fa-tasks me-2"></i> وظایف کارمند
                </summary>
                <ul class="submenu-items">
                    <li><a class="nav-link submenu-link" href="{{ route('staff.tasks') }}"><i class="fas fa-check-circle me-2"></i> وظایف</a></li>
                    <li><a class="nav-link submenu-link" href="{{ route('staff.reports') }}"><i class="fas fa-file-alt me-2"></i> گزارش‌ها</a></li>
                </ul>
            </details>
        </li>
    @endif

    <!-- منوهای نقش user -->
@if(auth()->check() && auth()->user()->role === 'user')
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-user me-2"></i> پروفایل من</a>
        </li>
    @endif

    <!-- منوی سفارش‌ها برای همه نقش‌ها -->
    <li class="nav-item">
        <a class="nav-link" href="/orders"><i class="fas fa-shopping-cart me-2"></i> سفارش‌ها</a>
    </li>
</ul>

