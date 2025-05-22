<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DocumentController;

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

   // روت‌های مدیریت کانکت ها
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
        Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
   // روت‌های مدیریت مدارک ها
    Route::get('/verify-documents', [DocumentController::class, 'verifyDocuments'])->name('verify.documents');
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approveDocument'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'rejectDocument'])->name('documents.reject');
    });

    