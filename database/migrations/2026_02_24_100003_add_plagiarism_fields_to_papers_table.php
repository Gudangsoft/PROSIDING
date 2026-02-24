<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom plagiarism ke papers
        Schema::table('papers', function (Blueprint $table) {
            $table->decimal('similarity_score', 5, 2)->nullable()->after('status'); // 0.00 - 100.00
            $table->string('plagiarism_tool')->nullable()->after('similarity_score'); // iThenticate, Turnitin, dll
            $table->text('plagiarism_note')->nullable()->after('plagiarism_tool');
            $table->timestamp('plagiarism_checked_at')->nullable()->after('plagiarism_note');
        });
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn(['similarity_score', 'plagiarism_tool', 'plagiarism_note', 'plagiarism_checked_at']);
        });
    }
};
