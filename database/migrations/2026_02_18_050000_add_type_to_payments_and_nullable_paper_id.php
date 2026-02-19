<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK constraint on paper_id, make it nullable
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['paper_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('paper_id')->nullable()->change();
            $table->string('type', 30)->default('paper')->after('id'); // 'paper' or 'participant'
        });

        // Re-add FK but nullable
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['paper_id']);
            $table->dropColumn('type');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('paper_id')->nullable(false)->change();
            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
        });
    }
};
