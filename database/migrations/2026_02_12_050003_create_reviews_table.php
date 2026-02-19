<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('comments')->nullable();
            $table->text('comments_for_editor')->nullable();
            $table->enum('recommendation', ['accept', 'minor_revision', 'major_revision', 'reject'])->nullable();
            $table->unsignedTinyInteger('score')->nullable(); // 1-100
            $table->string('review_file_path')->nullable();
            $table->string('review_file_name')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
