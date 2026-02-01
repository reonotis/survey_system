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
        Schema::create('analytics_dashboard_rows', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->bigInteger('form_setting_id')->comment('フォーム設定ID');
            $table->tinyInteger('row_index')->comment('行番号');
            $table->tinyInteger('layout_type')->nullable()->comment('レイアウト種別');
            $table->json('layout_definition')->comment('レイアウト詳細（列幅）');
            $table->bigInteger('analytics_dashboard_widget_id_1')->nullable()->comment('分析ウィジェットID');
            $table->bigInteger('analytics_dashboard_widget_id_2')->nullable()->comment('分析ウィジェットID');
            $table->bigInteger('analytics_dashboard_widget_id_3')->nullable()->comment('分析ウィジェットID');
            $table->bigInteger('analytics_dashboard_widget_id_4')->nullable()->comment('分析ウィジェットID');
            $table->bigInteger('analytics_dashboard_widget_id_5')->nullable()->comment('分析ウィジェットID');
            $table->bigInteger('analytics_dashboard_widget_id_6')->nullable()->comment('分析ウィジェットID');

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
        Schema::dropIfExists('analytics_dashboard_rows');
    }
};
