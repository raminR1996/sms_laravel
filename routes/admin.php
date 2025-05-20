<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingsController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
        Route::get('/settings/{id}/edit', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{id}', [SettingsController::class, 'update'])->name('settings.update'); // تغییر به PUT
        Route::delete('/settings/{id}', [SettingsController::class, 'destroy'])->name('settings.destroy');


        // روت‌های مدیریت کاربران
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    