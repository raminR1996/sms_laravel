<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // مقدار پیش‌فرض برای تنظیمات
        DB::table('settings')->insert([
            [
                'key' => 'site_title',
                'value' => 'درودزن پیام',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_description',
                'value' => 'سیستم ارسال پیامک درودزن پیام، راه‌حل سریع و مطمئن برای اطلاع‌رسانی و تبلیغات شما.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_sms_number',
                'value' => '1000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};