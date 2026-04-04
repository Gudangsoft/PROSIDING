<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['participant', 'presenter', 'reviewer', 'committee', 'all'])->default('all');
            $table->text('html_template');
            $table->string('background_image')->nullable(); // Background image path
            $table->string('signature_image')->nullable();  // Signature image path
            $table->string('stamp_image')->nullable();      // Stamp/seal image path
            $table->string('logo_image')->nullable();       // Logo image path
            $table->enum('orientation', ['landscape', 'portrait'])->default('landscape');
            $table->enum('paper_size', ['a4', 'letter', 'legal'])->default('a4');
            $table->json('settings')->nullable(); // Additional settings (colors, fonts, etc.)
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
