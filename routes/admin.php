<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\GroupSmsController;
use App\Http\Controllers\Admin\LineController;
use App\Http\Controllers\Admin\PackageController;


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingsController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
        Route::get('/settings/{id}/edit', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{id}', [SettingsController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{id}', [SettingsController::class, 'destroy'])->name('settings.destroy');

        // روت‌های مدیریت کاربران
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // روت‌های مدیریت کانتکت‌ها
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/data', [ContactController::class, 'getData'])->name('contacts.data');
        Route::get('/contacts/village/{village_id}', [ContactController::class, 'showVillageContacts'])->name('contacts.village');
        Route::get('/contacts/village/{id}/data', [ContactController::class, 'villageContactsData'])->name('contacts.village.data');
        Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
        Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

        // روت‌های مدیریت مدارک
        Route::get('/verify-documents', [DocumentController::class, 'verifyDocuments'])->name('verify.documents');
        Route::post('/documents/{document}/approve', [DocumentController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/documents/{document}/reject', [DocumentController::class, 'rejectDocument'])->name('documents.reject');

        // خطوط
        Route::get('/lines', [LineController::class, 'index'])->name('lines.index');
        Route::get('/lines/create', [LineController::class, 'create'])->name('lines.create');
        Route::post('/lines', [LineController::class, 'store'])->name('lines.store');
        Route::get('/lines/{line}/edit', [LineController::class, 'edit'])->name('lines.edit');
        Route::put('/lines/{line}', [LineController::class, 'update'])->name('lines.update');
        Route::delete('/lines/{line}', [LineController::class, 'destroy'])->name('lines.destroy');
        Route::post('/lines/{line}/toggle', [LineController::class, 'toggleStatus'])->name('lines.toggle');

        // روت‌های مدیریت بسته‌ها
        Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
        Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
        Route::post('/packages/{package}/toggle', [PackageController::class, 'toggleStatus'])->name('packages.toggle');

        // روت‌های مدیریت درخواست‌های پیامک گروهی
        Route::get('/group-sms-requests', [GroupSmsController::class, 'index'])->name('group-sms-requests');
        Route::post('/group-sms-requests/{groupSmsRequest}/approve', [GroupSmsController::class, 'approve'])->name('group-sms.approve');
    });