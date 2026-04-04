<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->string('video_presentation_url')->nullable()->after('meeting_scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn('video_presentation_url');
        });
    }
};
