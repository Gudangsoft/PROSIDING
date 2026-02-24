<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conference_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paper_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['participant', 'presenter', 'reviewer', 'committee'])->default('participant');
            $table->string('certificate_number')->unique();
            $table->string('recipient_name');
            $table->string('file_path')->nullable(); // pre-generated PDF path
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        // Template HTML untuk sertifikat per conference
        Schema::table('conferences', function (Blueprint $table) {
            $table->text('certificate_template')->nullable()->after('brochure');
            $table->boolean('certificate_enabled')->default(false)->after('certificate_template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn(['certificate_template', 'certificate_enabled']);
        });
    }
};
