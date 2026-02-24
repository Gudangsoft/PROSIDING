<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah bidang keahlian reviewer untuk auto-assign
        Schema::table('users', function (Blueprint $table) {
            $table->json('reviewer_topics')->nullable()->after('photo'); // topik keahlian reviewer
            $table->integer('max_review_load')->default(5)->after('reviewer_topics'); // maks jumlah paper yang di-review
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reviewer_topics', 'max_review_load']);
        });
    }
};
