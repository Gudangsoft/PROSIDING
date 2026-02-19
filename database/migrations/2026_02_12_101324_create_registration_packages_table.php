<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->string('name');              // e.g. "Peserta Seminar 2025"
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->string('description')->nullable(); // subtitle
            $table->json('features')->nullable();      // ["Akses Full Day", "E-Sertificate", ...]
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_packages');
    }
};
