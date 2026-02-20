<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keynote_speakers', function (Blueprint $table) {
            $table->boolean('show_on_web')->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('keynote_speakers', function (Blueprint $table) {
            $table->dropColumn('show_on_web');
        });
    }
};
