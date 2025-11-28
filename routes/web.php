<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\WelcomeController;

// クライアントには提供しない機能のルーティング
Route::middleware('not_client')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
});

// 申込フォーム
Route::get('/form/{route_name}', [FormController::class, 'index'])->name('form_index');
Route::post('/form/{route_name}', [FormController::class, 'register'])->name('form_register');
Route::get('/form/{route_name}/complete', [FormController::class, 'complete'])->name('form_complete');

// 各認証タイプのルートを読み込み
require __DIR__.'/user.php';
require __DIR__.'/admin.php';
require __DIR__.'/owner.php';
