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
        Schema::create('group_sms_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // کاربر درخواست‌دهنده
            $table->string('line_number'); // شماره خط ارسالی
            $table->text('message'); // متن پیام
            $table->json('village_ids'); // لیست ID روستاها (به صورت آرایه JSON)
            $table->unsignedInteger('sms_count'); // تعداد کل پیامک‌ها
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // وضعیت تأیید
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // مدیر تأییدکننده
            $table->timestamp('approved_at')->nullable(); // زمان تأیید
            $table->string('batch_id')->nullable(); // شناسه دسته (برای API)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_sms_requests');
    }
};
