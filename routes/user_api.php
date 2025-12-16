<?php

use App\Http\Controllers\User\FormItemSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth.api:user')->group(function () {
    // アンケート項目の並び替え
    Route::prefix('form')->group(function () {

        Route::post('/{form_setting}/update-item-order', [FormItemSettingController::class, 'updateItemOrder'])->name('user_api_forms_update_item_order');
        Route::post('/{form_setting}/register-form-item', [FormItemSettingController::class, 'registerFormItem'])->name('user_api_register_form_item');

    });
});

