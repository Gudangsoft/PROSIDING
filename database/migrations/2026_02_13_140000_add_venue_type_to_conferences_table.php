<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->enum('venue_type', ['offline', 'online', 'hybrid'])->default('offline')->after('venue');
            $table->string('online_url')->nullable()->after('venue_type');
        });
    }

    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn(['venue_type', 'online_url']);
        });
    }
};
