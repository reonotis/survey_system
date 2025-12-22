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
        Schema::create('form_settings', function (Blueprint $table) {
            $table->id()->comment('アンケートID');
            $table->string('host_name')->comment('ホスト名');
            $table->string('form_name')->comment('フォーム管理名');
            $table->string('title')->comment('アンケートタイトル');
            $table->string('route_name')->comment('ルーティング名');
            $table->string('admin_email')->comment('事務局のメールアドレス（通知用）');
            $table->timestamp('start_date')->nullable()->comment('応募開始日時');
            $table->timestamp('end_date')->nullable()->comment('応募終了日時');
            $table->integer('max_applications')->nullable()->comment('最大申込上限数');
            $table->string('image_directory')->nullable()->comment('画像保存ディレクトリ');
            $table->string('css_filename')->nullable()->comment('CSSファイル名');
            $table->string('banner_filename')->nullable()->comment('バナーファイル名');
            $table->tinyInteger('publication_status')->default(0)->comment('公開状態');

            $table->boolean('is_draft_item')->default(0)->comment('項目を編集中か否か');

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->unsignedInteger('created_by_admin')->nullable()->comment('作成者');
            $table->unsignedInteger('created_by_user')->nullable()->comment('作成者');
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes()->comment('削除日時');
        });

        // テーブルコメントを追加
        DB::statement("ALTER TABLE form_settings COMMENT '申込フォーム設定テーブル'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_settings');
    }
};
