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
        Schema::create('applications', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->bigInteger('form_setting_id')->comment('フォーム設定ID');
            $table->string('name', '50')->nullable()->comment('名前(項目を分けない場合のフルネーム/もしくは性)');
            $table->string('name_last', '50')->nullable()->comment('名前');
            $table->string('kana', '50')->nullable()->comment('ヨミ(項目を分けない場合のフルネーム/もしくはセイ)');
            $table->string('kana_last', '50')->nullable()->comment('ナマエ');
            $table->string('email', '50')->nullable()->comment('メールアドレス');
            $table->string('tel', '20')->nullable()->comment('電話番号');
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
        Schema::dropIfExists('applications');
    }
};
