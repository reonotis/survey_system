<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\FormItemSettingController;

Route::prefix('owner')->middleware('auth.api:owner')->group(function () {
    // アンケート項目の並び替え
    Route::prefix('form')->group(function () {
        Route::post('/{form_setting}/update-item-order', [FormItemSettingController::class, 'updateItemOrder'])->name('owner_api_forms_update_item_order');
    });
});

