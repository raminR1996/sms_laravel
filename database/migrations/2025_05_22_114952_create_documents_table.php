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
        Schema::create('documents', function (Blueprint $table) {
          $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ارتباط با جدول users
            $table->string('national_id_photo')->nullable(); // عکس کارت ملی
            $table->string('selfie_with_id_photo')->nullable(); // سلفی با کارت ملی
            $table->boolean('verified')->default(false); // تأیید توسط مدیر
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
