<?php

use App\Http\Controllers\User\FormItemSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth.api:user')->group(function () {
    Route::prefix('form')->group(function () {

//        Route::post('/{form_setting}/update-item-order', [FormItemSettingController::class, 'updateItemOrder'])->name('user_api_forms_update_item_order');
//        Route::post('/{form_setting}/register-form-item', [FormItemSettingController::class, 'registerFormItem'])->name('user_api_register_form_item');

        Route::post('/{form_setting}/draft-item-delete', [FormItemSettingController::class, 'draftItemDelete'])->name('user_form_draft_item_delete');
        Route::post('/{form_setting}/draft-add-item', [FormItemSettingController::class, 'draftAddItem'])->name('user_form_draft_add_item');
        Route::post('/{form_setting}/draft-sort-change', [FormItemSettingController::class, 'draftSortChange'])->name('user_form_draft_sort_change');
        Route::post('/{form_setting}/draft-item-save', [FormItemSettingController::class, 'draftItemSave'])->name('user_form_draft_item_save');


    });
});

