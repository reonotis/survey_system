<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth.api:admin')->group(function () {
    // APIルートをここに追加
});

