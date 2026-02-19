<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_guidelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('template_file')->nullable();
            $table->integer('max_pages')->nullable();
            $table->integer('min_pages')->nullable();
            $table->string('paper_format')->nullable();
            $table->string('citation_style')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_guidelines');
    }
};
