<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\AuthenticatedSessionController;

Route::prefix('user')->name('user.')->group(function () {
    // 認証ルート
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // 認証が必要なルート
    Route::middleware('auth.custom:web')->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
    });
});
