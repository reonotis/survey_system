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
        Schema::create('form_item_drafts', function (Blueprint $table) {
            $table->id()->comment('項目ID');
            $table->bigInteger('form_setting_id')->comment('フォーム設定ID');
            $table->bigInteger('form_item_id')->nullable()->comment('項目設定ID');
            $table->tinyInteger('item_type')->comment('項目種別');
            $table->boolean('field_required')->default(false)->comment('必須');
            $table->string('item_title', '50')->nullable()->comment('項目名');
            $table->json('value_list')->nullable()->comment('選択項目');
            $table->json('details')->nullable()->comment('詳細');
            $table->text('annotation_text')->nullable()->comment('注釈文');
            $table->text('long_text')->nullable()->comment('文章');
            $table->tinyInteger('sort')->default(0)->comment('並び順');
            $table->softDeletes()->comment('削除日時');

        });

        // テーブルコメントを追加
        DB::statement("ALTER TABLE form_item_drafts COMMENT '項目設定一時保存テーブル'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_item_drafts');
    }
};
