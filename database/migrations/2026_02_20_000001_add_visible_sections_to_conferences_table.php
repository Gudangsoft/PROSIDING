<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->json('visible_sections')->nullable()->after('certificate_generation_mode')
                ->comment('JSON array of section IDs that are visible on the public website. Null means all sections visible.');
        });
    }

    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn('visible_sections');
        });
    }
};
