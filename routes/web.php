<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;


Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
Route::get('otp-login', [OtpController::class, 'showOtpForm'])->name('otp.login');
Route::post('otp-send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
