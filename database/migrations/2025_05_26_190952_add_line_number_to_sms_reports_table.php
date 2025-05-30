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
        Schema::table('sms_reports', function (Blueprint $table) {
           
            $table->string('line_number')->after('type'); // ستون شماره خط بعد از type
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_reports', function (Blueprint $table) {
            $table->dropColumn('line_number');
        });
    }
};
