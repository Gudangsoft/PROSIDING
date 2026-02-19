<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->string('keywords')->nullable();
            $table->string('topic')->nullable();
            $table->json('authors_meta')->nullable(); // co-authors info
            $table->enum('status', [
                'submitted',
                'screening',
                'in_review',
                'revision_required',
                'revised',
                'accepted',
                'rejected',
                'payment_pending',
                'payment_uploaded',
                'payment_verified',
                'deliverables_pending',
                'completed',
            ])->default('submitted');
            $table->text('editor_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('papers');
    }
};
