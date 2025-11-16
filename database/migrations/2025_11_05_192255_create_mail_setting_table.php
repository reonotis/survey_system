<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mail_setting', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->bigInteger('form_setting_id')->comment('フォーム設定ID');

            $table->boolean('notification_mail_flg')->nullable()->comment('通知メール文言利用フラグ');
            $table->string('notification_mail_title', 100)->nullable()->comment('通知メール題名');
            $table->string('notification_mail_address', 500)->nullable()->comment('通知メール送信先');
            $table->text('notification_mail_message')->nullable()->comment('通知メール文言メッセージ');

            $table->boolean('auto_reply_mail_flg')->nullable()->comment('自動返信メール利用フラグ');
            $table->string('auto_reply_mail_title', 100)->nullable()->comment('自動返信メール題名');
            $table->text('auto_reply_mail_message')->nullable()->comment('自動返信メール文言');

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_setting');
    }
};
