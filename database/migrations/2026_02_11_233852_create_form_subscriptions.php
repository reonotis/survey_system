<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_subscriptions', function (Blueprint $table) {
            $table->id()->comment('主キー');

            $table->foreignId('form_setting_id')->constrained()->cascadeOnDelete()
                ->comment('対象フォームID');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()
                ->comment('ユーザーID');

            $table->string('stripe_subscription_id')->unique()->comment('StripeのサブスクリプションID（sub_xxxxx）');
            $table->string('status')->index()->comment('Stripe上の契約状態（active / canceled / past_due など）');
            $table->timestamp('current_period_end')->nullable()->comment('現在の契約期間終了日時（解約後もこの日時までは利用可能）');

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes()->comment('削除日時');

            $table->index(['form_setting_id', 'status'], 'idx_form_subscription_form_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_subscriptions');
    }
};
