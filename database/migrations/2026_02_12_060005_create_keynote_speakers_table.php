<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keynote_speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['opening_speech', 'keynote_speaker', 'narasumber', 'moderator_host'])->default('keynote_speaker');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('institution')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('topic')->nullable();
            $table->dateTime('schedule')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keynote_speakers');
    }
};
