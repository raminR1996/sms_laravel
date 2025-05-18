<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
