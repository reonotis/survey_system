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
//        Schema::create('owners', function (Blueprint $table) {
//            $table->id()->comment('オーナーID');
//            $table->string('name')->comment('オーナー名');
//            $table->string('email')->unique()->comment('メールアドレス');
//            $table->timestamp('email_verified_at')->nullable()->comment('メール認証日時');
//            $table->string('password')->comment('パスワード');
//            $table->rememberToken()->comment('ログイン状態保持トークン');
//            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
//            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日時');
//            $table->softDeletes()->comment('削除日時');
//        });
//
//        // テーブルコメントを追加
//        DB::statement("ALTER TABLE owners COMMENT 'オーナーテーブル'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('owners');
    }
};
