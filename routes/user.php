<?php

declare(strict_types=1);

use App\Http\Controllers\User\Auth\RegisteredUserController;
use App\Http\Controllers\User\Auth\UserAuthController;
use App\Http\Controllers\User\FormApplicationController;
use App\Http\Controllers\User\FormBasicSettingController;
use App\Http\Controllers\User\FormDesignSettingController;
use App\Http\Controllers\User\FormItemSettingController;
use App\Http\Controllers\User\FormMailSettingController;
use App\Http\Controllers\User\FormMessageSettingController;
use App\Http\Controllers\User\FormSettingController;
use App\Http\Controllers\User\FormWinningSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {

    // クライアントには提供しない機能のルーティング
    Route::middleware('not_client')->group(function () {
        // ユーザー登録
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('user_register');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('user_register');
    });

    // 認証ルート
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('user_login');
    Route::post('/login', [UserAuthController::class, 'login']);

    // 認証が必要なルート
    Route::middleware('auth.custom:user')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('user_logout');

        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user_dashboard');

        // アンケート
        Route::prefix('form_setting')->group(function () {
            Route::get('/list', [FormSettingController::class, 'index'])->name('user_form_index'); // 応募フォーム一覧
            Route::post('/get-form-list', [FormSettingController::class, 'getFormData'])->name('user_form_get_form_list');
            Route::get('/create', [FormSettingController::class, 'create'])->name('user_form_create');
            Route::post('/register', [FormSettingController::class, 'store'])->name('user_form_register');

            // 応募者一覧
            Route::get('/{form_setting}/application-list', [FormApplicationController::class, 'show'])->name('user_form_application_list');
            Route::post('/{form_setting}/get-application-data', [FormApplicationController::class, 'getApplicationData'])->name('user_form_get_application_list');
            Route::post('/{form_setting}/update-display-items', [FormApplicationController::class, 'displayItemsUpdate'])->name('user_form_update_display_items');

            // 基本設定
            Route::get('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'index'])->name('user_form_basic_setting');
            Route::post('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'update'])->name('user_form_basic_setting_update');

            // 項目設定
            Route::get('/{form_setting}/item-setting', [FormItemSettingController::class, 'index'])->name('user_form_item_setting');
            Route::post('/{form_setting}/register-form-item', [FormItemSettingController::class, 'registerFormItem'])->name('user_form_register_form_item');
            Route::post('/{form_setting}/update-form-item/{form_item}', [FormItemSettingController::class, 'updateFormItem'])->name('user_form_update_form_item');
            Route::delete('/{form_setting}/delete-form-item/{form_item}', [FormItemSettingController::class, 'deleteFormItem'])->name('user_form_delete_form_item');

            // メッセージ設定
            Route::get('/{form_setting}/message-setting', [FormMessageSettingController::class, 'index'])->name('user_form_message_setting');
            Route::post('/{form_setting}/message-setting', [FormMessageSettingController::class, 'update'])->name('user_form_message_setting_update');

            // メール設定
            Route::get('/{form_setting}/mail-setting', [FormMailSettingController::class, 'index'])->name('user_form_mail_setting');
            Route::post('/{form_setting}/mail-setting', [FormMailSettingController::class, 'update'])->name('user_form_mail_setting_update');

            // 当選設定
            Route::get('/{form_setting}/winning-setting', [FormWinningSettingController::class, 'index'])->name('user_form_winning_setting');

            // デザイン設定
            Route::get('/{form_setting}/design-setting', [FormDesignSettingController::class, 'index'])->name('user_form_design_setting');
        });

    });

});
