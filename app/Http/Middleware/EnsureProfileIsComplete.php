<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // روت‌های مجاز برای کاربرانی که پروفایل یا مدارکشون کامل نیست
        $allowedRoutes = [
            'dashboard',
            'profile.edit',
            'profile.update',
            'profile.destroy',
            'profile.complete',
            'profile.complete.store',
            'documents.upload.form',
            'documents.upload',
            'logout',
        ];

        $currentRoute = $request->route()->getName();

        // اگر کاربر ادمین یا کارمند است، محدودیت اعمال نمی‌شه
        if ($user->isAdmin() || $user->isStaff()) {
            return $next($request);
        }

        // اگر پروفایل تکمیل نشده، به صفحه تکمیل پروفایل هدایت کن
        if (!$user->profile_completed) {
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('profile.complete')
                    ->with('error', 'لطفاً ابتدا پروفایل خود را تکمیل کنید.');
            }
        }

        // اگر مدارک تأیید نشده‌اند، دسترسی به صفحات غیرمجاز محدود بشه
        if ($user->profile_completed && !$user->documents_verified) {
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->back()
                    ->with('error', 'مدارک شما هنوز توسط مدیریت تأیید نشده است.');
            }
        }

        // اگر مدارک هنوز آپلود نشده‌اند، اجازه دسترسی به صفحه آپلود مدارک بده
        if ($user->profile_completed && !$user->documents && in_array($currentRoute, ['documents.upload.form', 'documents.upload'])) {
            return $next($request);
        }

        // اگر همه چیز درست باشه، درخواست رو ادامه بده
        return $next($request);
    }
}