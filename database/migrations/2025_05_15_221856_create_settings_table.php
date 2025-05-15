<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->default('درودزن پیام');
            $table->text('site_description')->nullable();
            $table->string('default_sms_number')->nullable();
            $table->timestamps();
        });

        // مقدار پیش‌فرض برای تنظیمات
        DB::table('settings')->insert([
            'site_title' => 'درودزن پیام',
            'site_description' => 'سیستم ارسال پیامک درودزن پیام، راه‌حل سریع و مطمئن برای اطلاع‌رسانی و تبلیغات شما.',
            'default_sms_number' => '1000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
