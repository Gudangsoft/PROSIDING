<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peer review history / paper status audit log
        Schema::create('paper_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->string('action')->nullable(); // e.g. 'status_changed', 'review_submitted', 'payment_verified'
            $table->text('notes')->nullable();
            $table->json('meta')->nullable(); // extra context (score, reviewer name, etc.)
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
        });

        // Cross-submission similarity pairs
        Schema::create('paper_similarities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('similar_paper_id')->constrained('papers')->cascadeOnDelete();
            $table->decimal('similarity_percent', 5, 2)->default(0);
            $table->string('compared_field')->default('title_abstract'); // or 'full_text'
            $table->timestamp('checked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['paper_id', 'similar_paper_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_similarities');
        Schema::dropIfExists('paper_status_logs');
    }
};
