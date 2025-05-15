<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
Route::get('otp-login', [OtpController::class, 'showOtpForm'])->name('otp.login');
Route::post('otp-send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'storeCompleteForm'])->name('profile.complete.store');
});


Route::middleware(['auth','verified','profile.complete'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'storeCompleteForm'])->name('profile.complete.store');
});

require __DIR__.'/auth.php';
