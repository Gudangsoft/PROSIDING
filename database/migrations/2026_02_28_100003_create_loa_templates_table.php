<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loa_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['paper', 'abstract', 'all'])->default('all');
            $table->longText('html_template');
            $table->string('background_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('signature_name')->nullable();
            $table->string('signature_position')->nullable();
            $table->string('signature2_image')->nullable();
            $table->string('signature2_name')->nullable();
            $table->string('signature2_position')->nullable();
            $table->string('stamp_image')->nullable();
            $table->string('letterhead_image')->nullable();
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->string('paper_size')->default('a4');
            $table->json('settings')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loa_templates');
    }
};
