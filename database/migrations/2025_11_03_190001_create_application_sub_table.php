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
        Schema::create('application_sub', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->bigInteger('application_id')->comment('フォーム設定ID');
            $table->bigInteger('form_item_id')->comment('項目設定ID');

            $table->tinyInteger('answer')->nullable()->comment('回答値');
            $table->string('answer_text')->comment('回答時点のテキスト');

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->softDeletes()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_sub');
    }
};
