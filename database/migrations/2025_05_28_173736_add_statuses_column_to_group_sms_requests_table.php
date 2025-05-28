<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_sms_requests', function (Blueprint $table) {
            $table->json('statuses')->nullable()->after('batch_id');
            $table->json('datetimes')->nullable()->after('statuses');
        });
    }

    public function down(): void
    {
        Schema::table('group_sms_requests', function (Blueprint $table) {
            $table->dropColumn(['statuses', 'datetimes']);
        });
    }
};