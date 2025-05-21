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
        Schema::create('contacts', function (Blueprint $table) {
             $table->id();
            $table->foreignId('village_id')->constrained()->onDelete('cascade'); // ارتباط با جدول روستاها
            $table->string('mobile_number'); // شماره موبایل
            $table->string('full_name')->nullable(); // نام کامل (اختیاری)
            $table->date('birth_date')->nullable(); // تاریخ تولد (اختیاری)
            $table->enum('gender', ['male', 'female']); // جنسیت (اجباری)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
