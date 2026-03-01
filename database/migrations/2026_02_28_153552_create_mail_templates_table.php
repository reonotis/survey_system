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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('template_name')->comment('テンプレート名');
            $table->string('subject')->comment('題名');
            $table->longText('body')->comment('本文');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->unsignedInteger('created_by')->comment('作成者');
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.　　
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_templates');
    }
};
