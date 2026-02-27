<?php

declare(strict_types=1);

use App\Http\Controllers\User\Auth\RegisteredUserController;
use App\Http\Controllers\User\Auth\UserAuthController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\FormAnalyticsController;
use App\Http\Controllers\User\CsvDownloadController;
use App\Http\Controllers\User\FormApplicationController;
use App\Http\Controllers\User\FormBasicSettingController;
use App\Http\Controllers\User\FormDesignSettingController;
use App\Http\Controllers\User\FormItemSettingController;
use App\Http\Controllers\User\FormMailSettingController;
use App\Http\Controllers\User\MailTemplateController;
use App\Http\Controllers\User\FormMessageSettingController;
use App\Http\Controllers\User\FormSettingController;
use App\Http\Controllers\User\FormWinningSettingController;
use App\Http\Controllers\User\FormBillingController;
use App\Http\Controllers\User\PreviewController;
use App\Http\Controllers\User\TinymceController;
use App\Http\Controllers\User\TinymceMailController;
use App\Http\Controllers\User\MemberSettingController;
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
    Route::middleware(['auth.custom:user', 'belongs_host', 'access_form_setting'])->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('user_logout');

        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user_dashboard');

        // フォームリストを取得
        Route::post('/get-form-list', [FormSettingController::class, 'getFormData'])->name('user_get_form_list');

        // メールテンプレート
        Route::get('/mail-template', [MailTemplateController::class, 'index'])->name('user_mail_template');

        // 問い合わせ
        Route::get('/contact', [ContactController::class, 'index'])->name('user_contact');
        Route::post('/contact', [ContactController::class, 'send'])->name('user_contact_send');

        // HTMLメール
        Route::get('/sample-mail', [TinymceController::class, 'index'])->name('user_sample_mail');
        Route::post('/tinymce/upload', [TinymceController::class, 'upload'])->name('user_sample_mail_tinymce_upload');
        Route::post('/tinymce/mail/send', [TinymceMailController::class, 'send'])->name('user_sample_mail_tinymce_send');

        // アンケート
        Route::prefix('form_setting')->group(function () {
            Route::get('/create', [FormSettingController::class, 'create'])->name('user_form_create');
            Route::post('/register', [FormSettingController::class, 'store'])->name('user_form_register');
            Route::post('/{form_setting}/delete', [FormSettingController::class, 'delete'])->name('user_form_delete');

            // 基本設定
            Route::get('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'index'])->name('user_form_basic_setting');
            Route::post('/{form_setting}/basic-setting', [FormBasicSettingController::class, 'update'])->name('user_form_basic_setting_update');

            // メッセージ設定
            Route::get('/{form_setting}/message-setting', [FormMessageSettingController::class, 'index'])->name('user_form_message_setting');
            Route::post('/{form_setting}/message-setting', [FormMessageSettingController::class, 'update'])->name('user_form_message_setting_update');

            // メール設定
            Route::get('/{form_setting}/mail-setting', [FormMailSettingController::class, 'index'])->name('user_form_mail_setting');
            Route::post('/{form_setting}/mail-setting', [FormMailSettingController::class, 'update'])->name('user_form_mail_setting_update');

            // デザイン設定
            Route::get('/{form_setting}/design-setting', [FormDesignSettingController::class, 'index'])->name('user_form_design_setting');
            Route::post('/{form_setting}/design-setting', [FormDesignSettingController::class, 'update'])->name('user_form_design_setting');


            // 項目設定
            Route::get('/{form_setting}/item-setting', [FormItemSettingController::class, 'index'])->name('user_form_item_setting');
            Route::post('/{form_setting}/all-draft-delete', [FormItemSettingController::class, 'allDraftDelete'])->name('user_form_all_draft_delete');
            Route::post('/{form_setting}/reset', [FormItemSettingController::class, 'resetDraftItem'])->name('user_form_reset_draft_item');
            Route::post('/{form_setting}/draft-add-item', [FormItemSettingController::class, 'draftAddItem'])->name('user_form_draft_add_item');
            Route::post('/{form_setting}/draft-sort-change', [FormItemSettingController::class, 'draftSortChange'])->name('user_form_draft_sort_change');
            Route::post('/{form_setting}/draft-item-save', [FormItemSettingController::class, 'draftItemSave'])->name('user_form_draft_item_save');
            Route::post('/{form_setting}/save-form-items', [FormItemSettingController::class, 'saveFormItems'])->name('user_form_save_form_items');


            // TODO メンバー
            Route::get('/{form_setting}/member-list', [MemberSettingController::class, 'index'])->name('user_form_member_list');


            // 応募者一覧
            Route::get('/{form_setting}/application-list', [FormApplicationController::class, 'show'])->name('user_form_application_list');
            Route::post('/{form_setting}/get-application-data', [FormApplicationController::class, 'getApplicationData'])->name('user_form_get_application_list');
            Route::post('/{form_setting}/update-display-items', [FormApplicationController::class, 'displayItemsUpdate'])->name('user_form_update_display_items');
            Route::post('/{form_setting}/csv-download', [CsvDownloadController::class, 'download'])->name('user_form_csv_download');

            // 応募分析
            Route::get('/{form_setting}/analytics', [FormAnalyticsController::class, 'index'])->name('user_form_analytics');
            Route::post('/{form_setting}/analytics/widget-row-register', [FormAnalyticsController::class, 'registerWidgetRow'])->name('user_form_analytics_add_widget_row');
            Route::post('/{form_setting}/analytics/widget-row-delete', [FormAnalyticsController::class, 'widgetRowDelete'])->name('user_form_analytics_widget_row_delete');
            Route::post('/{form_setting}/analytics/add-widget', [FormAnalyticsController::class, 'addWidget'])->name('user_form_analytics_add_widget');

            // 当選設定
            Route::get('/{form_setting}/winning-setting', [FormWinningSettingController::class, 'index'])->name('user_form_winning_setting');


            // 課金関連
            Route::post('/{form_setting}/billing/checkout', [FormBillingController::class, 'checkout'])->name('user_form_billing_checkout');
            Route::get('/{form_setting}/billing/success', [FormBillingController::class, 'success'])->name('user_form_billing_checkout_success');
            Route::get('/{form_setting}/billing/cancel', [FormBillingController::class, 'cancel'])->name('user_form_billing_checkout_cancel');


            // プレビュー
            Route::get('/{form_setting}/preview', [PreviewController::class, 'index'])->name('user_form_preview');
        });

    });

});
