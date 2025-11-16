<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\FormSettingController;

Route::prefix('admin')->group(function () {
    // 認証ルート
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin_login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // 認証が必要なルート
    Route::middleware('auth.custom:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin_logout');
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // アンケート
        Route::get('/form', [FormSettingController::class, 'index'])->name('admin_form_index');
        Route::post('/form/get-form-data', [FormSettingController::class, 'getFormData'])->name('admin_form_data');
        Route::get('/surveys/create', [FormSettingController::class, 'create'])->name('admin_surveys_create');
        Route::post('/surveys/register', [FormSettingController::class, 'register'])->name('admin_surveys_register');
        Route::get('/surveys/{survey}/edit', [FormSettingController::class, 'edit'])->name('admin_surveys_edit');
    });
});
