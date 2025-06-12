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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->comment('نوع تراکنش: credit (افزایش شارژ), debit (کاهش شارژ)');
            $table->unsignedInteger('sms_count')->comment('تعداد پیامک تغییر یافته');
            $table->unsignedBigInteger('amount')->nullable()->comment('مبلغ مرتبط (برای خرید بسته‌ها)');
            $table->string('description')->comment('توضیحات تراکنش');
            $table->unsignedInteger('sms_balance_after')->comment('مانده شارژ پس از تراکنش');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
