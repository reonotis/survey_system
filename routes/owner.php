<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\Auth\OwnerAuthController;
use App\Http\Controllers\Owner\Auth\RegisteredUserController;
use App\Http\Controllers\Owner\FormApplicationController;
use App\Http\Controllers\Owner\FormBasicSettingController;
use App\Http\Controllers\Owner\FormDesignSettingController;
use App\Http\Controllers\Owner\FormItemSettingController;
use App\Http\Controllers\Owner\FormMailSettingController;
use App\Http\Controllers\Owner\FormMessageSettingController;
use App\Http\Controllers\Owner\FormSettingController;
use App\Http\Controllers\Owner\FormWinningSettingController;

Route::prefix('owner')->group(function () {

    // クライアントには提供しない機能のルーティング
    Route::middleware('not_client')->group(function () {
        // オーナーユーザー登録
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('owner_register');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('owner_register');
    });

    // 認証ルート
    Route::get('/login', [OwnerAuthController::class, 'showLoginForm'])->name('owner_login');
    Route::post('/login', [OwnerAuthController::class, 'login']);

    // 認証が必要なルート
    Route::middleware('auth.custom:owner')->group(function () {
        Route::post('/logout', [OwnerAuthController::class, 'logout'])->name('owner_logout');

        Route::get('/dashboard', function () {
            return view('owner.dashboard');
        })->name('owner_dashboard');

        // アンケート
        Route::prefix('form_setting')->group(function () {
            Route::get('/list', [FormSettingController::class, 'index'])->name('owner_form_index'); // 応募フォーム一覧
            Route::post('/get-form-list', [FormSettingController::class, 'getFormData'])->name('owner_form_get_form_list');
            Route::get('/create', [FormSettingController::class, 'create'])->name('owner_form_create');
            Route::post('/register', [FormSettingController::class, 'store'])->name('owner_form_register');

            // 応募者一覧
            Route::get('/{form_setting}/application-list', [FormApplicationController::class, 'show'])->name('owner_form_application_list');
            Route::post('/{form_setting}/get-application-data', [FormApplicationController::class, 'getApplicationData'])->name('owner_form_get_application_list');
            Route::post('/{form_setting}/update-display-items', [FormApplicationController::class, 'displayItemsUpdate'])->name('owner_form_update_display_items');

            // 基本設定
            Route::get('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'index'])->name('owner_form_basic_setting');
            Route::post('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'update'])->name('owner_form_basic_setting_update');

            // 項目設定
            Route::get('/{form_setting}/item-setting', [FormItemSettingController::class, 'index'])->name('owner_form_item_setting');
            Route::post('/{form_setting}/register-form-item', [FormItemSettingController::class, 'registerFormItem'])->name('owner_form_register_form_item');
            Route::post('/{form_setting}/update-form-item/{form_item}', [FormItemSettingController::class, 'updateFormItem'])->name('owner_form_update_form_item');
            Route::delete('/{form_setting}/delete-form-item/{form_item}', [FormItemSettingController::class, 'deleteFormItem'])->name('owner_form_delete_form_item');

            // メッセージ設定
            Route::get('/{form_setting}/message-setting', [FormMessageSettingController::class, 'index'])->name('owner_form_message_setting');
            Route::post('/{form_setting}/message-setting', [FormMessageSettingController::class, 'update'])->name('owner_form_message_setting_update');

            // メール設定
            Route::get('/{form_setting}/mail-setting', [FormMailSettingController::class, 'index'])->name('owner_form_mail_setting');
            Route::post('/{form_setting}/mail-setting', [FormMailSettingController::class, 'update'])->name('owner_form_mail_setting_update');

            // 当選設定
            Route::get('/{form_setting}/winning-setting', [FormWinningSettingController::class, 'index'])->name('owner_form_winning_setting');

            // デザイン設定
            Route::get('/{form_setting}/design-setting', [FormDesignSettingController::class, 'index'])->name('owner_form_design_setting');
        });

        // ownerユーザー
        Route::prefix('user')->group(function () {
        });
    });
});
