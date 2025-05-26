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
        // مسیرهایی که از بررسی مستثنی هستند
        $exemptRoutes = [
            'payment.callback', // مسیر callback زرین‌پال
        ];

        $currentRoute = $request->route() ? $request->route()->getName() : null;

        // اگر مسیر جزو مستثنی‌ها باشد، ادامه بده
        if (in_array($currentRoute, $exemptRoutes)) {
            return $next($request);
        }

        $user = Auth::user();

        // اگر کاربر وارد نشده باشد، به صفحه ورود هدایت کن
        if (!$user) {
            return redirect()->route('login')->with('error', 'لطفاً ابتدا وارد شوید.');
        }

        // روت‌های مجاز برای کاربرانی که پروفایل یا مدارکشان کامل نیست
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

        // اگر کاربر ادمین یا کارمند است، محدودیت اعمال نشود
        if ($user->isAdmin() || $user->isStaff()) {
            return $next($request);
        }

        // اگر پروفایل تکمیل نشده باشد
        if (!$user->profile_completed) {
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('profile.complete')
                    ->with('error', 'لطفاً ابتدا پروفایل خود را تکádioفیل کنید.');
            }
        }

        // اگر مدارک تأیید نشده باشند
        if ($user->profile_completed && !$user->documents_verified) {
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('documents.upload.form')
                    ->with('error', 'مدارک شما هنوز توسط مدیریت تأیید نشده است. لطفاً مدارک را آپلود کنید.');
            }
        }

        // اگر مدارک هنوز آپلود نشده باشند، اجازه دسترسی به صفحه آپلود مدارک بده
        if ($user->profile_completed && !$user->documents()->exists() && in_array($currentRoute, ['documents.upload.form', 'documents.upload'])) {
            return $next($request);
        }

        return $next($request);
    }
}