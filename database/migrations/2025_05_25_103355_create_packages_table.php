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
        Schema::create('packages', function (Blueprint $table) {
           $table->id();
            $table->unsignedInteger('sms_count')->comment('تعداد پیامک در بسته');
            $table->unsignedBigInteger('price')->comment('قیمت بسته به تومان');
            $table->boolean('is_active')->default(true)->comment('وضعیت بسته: فعال/غیرفعال');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
