<?php

declare(strict_types=1);

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
        Schema::create('analytics_dashboard_widget', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->bigInteger('form_setting_id')->comment('フォーム設定ID');
            $table->bigInteger('form_item_id')->comment('項目設定ID');
            $table->string('analytics_title')->nullable()->comment('分析タイトル');
            $table->tinyInteger('display_type')->comment('表示形式');

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
        Schema::dropIfExists('analytics_dashboard_widget');
    }
};
