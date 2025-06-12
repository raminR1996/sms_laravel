<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupReportController;
use App\Http\Controllers\GroupSmsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', [RedirectController::class, 'redirectToOtpLogin']);
Route::get('/register', [RedirectController::class, 'redirectToOtpLogin']);
Route::get('/clear-cache', [CacheController::class, 'clearCache'])->name('clear.cache');
// مسیر callback بدون Middleware
Route::get('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback')
    ->withoutMiddleware(['auth', 'verified', 'EnsureProfileIsComplete']);

Route::get('/contact-form', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact-form', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('otp-login', [OtpController::class, 'showOtpForm'])->name('otp.login');
    Route::post('otp-send', [OtpController::class, 'sendOtp'])->name('otp.send');
    Route::get('otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'storeCompleteForm'])->name('profile.complete.store');
    Route::get('/documents/upload', [ProfileController::class, 'showDocumentsForm'])->name('documents.upload.form');
    Route::post('/documents/upload', [ProfileController::class, 'uploadDocuments'])->name('documents.upload');
});

Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// روت‌های شارژ پنل
    Route::get('/charge', [ChargeController::class, 'index'])->name('charge.index');
    Route::get('/successful-payments', [ChargeController::class, 'successfulPayments'])->name('successful_payments.index');
    Route::get('/purchased-packages', [ChargeController::class, 'purchasedPackages'])->name('purchased_packages.index');
    Route::get('/transactions', [ChargeController::class, 'transactions'])->name('transactions.index');
    Route::post('/payment/purchase', [PaymentController::class, 'purchase'])->name('payment.purchase');

    

    // Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// روت‌های ارسال پیامک
    Route::get('/send-sms/single', [SmsController::class, 'sendSingleSms'])->name('send.sms.single');
    Route::post('/send-sms/single', [SmsController::class, 'storeSingleSms'])->name('send.sms.single.store');

        // روت‌های ارسال پیامک گروهی
    Route::get('/send-sms/group', [GroupSmsController::class, 'sendGroupSms'])->name('send.sms.group');
    Route::post('/send-sms/group', [GroupSmsController::class, 'storeGroupSms'])->name('send.sms.group.store');

    // روت‌های گزارشات گروهی
    Route::get('/group-reports', [GroupReportController::class, 'index'])->name('group.reports.index');
    Route::get('/group-reports/data', [GroupReportController::class, 'getData'])->name('group.reports.data');
    Route::get('/group-reports/{id}/details', [GroupReportController::class, 'details'])->name('group.reports.details');
    Route::get('/group-reports/{id}/details/data', [GroupReportController::class, 'getDetailsData'])->name('group.reports.details.data');
    Route::get('/group-reports/{id}/update-status', [GroupReportController::class, 'updateStatus'])->name('group.reports.update.status');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/data', [ReportController::class, 'getData'])->name('reports.data'); // روت جدید برای AJAX
    Route::get('/reports/{id}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update.status');
    Route::get('/send-sms', [DashboardController::class, 'sendSms'])->name('send.sms');
    Route::middleware('role:staff,admin')->group(function () {
        Route::get('/contacts', [DashboardController::class, 'contacts'])->name('contacts.index');
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
