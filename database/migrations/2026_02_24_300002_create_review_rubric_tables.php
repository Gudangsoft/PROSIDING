<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Review rubric templates
        Schema::create('review_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('passing_score')->default(60);
            $table->timestamps();
        });

        // Criteria per rubric
        Schema::create('rubric_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_rubric_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->integer('weight')->default(1); // relative weight
            $table->integer('max_score')->default(10);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Scores per criterion per review
        Schema::create('review_criterion_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rubric_criterion_id')->constrained('rubric_criteria')->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_criterion_scores');
        Schema::dropIfExists('rubric_criteria');
        Schema::dropIfExists('review_rubrics');
    }
};
