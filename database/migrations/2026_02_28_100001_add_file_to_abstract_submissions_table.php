<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abstract_submissions', function (Blueprint $table) {
            $table->string('abstract_file_path')->nullable()->after('authors_meta');
            $table->string('abstract_file_name')->nullable()->after('abstract_file_path');
        });
    }

    public function down(): void
    {
        Schema::table('abstract_submissions', function (Blueprint $table) {
            $table->dropColumn(['abstract_file_path', 'abstract_file_name']);
        });
    }
};
