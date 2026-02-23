<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('key'); // e.g. welcome, payment_verified, payment_reminder, paper_submitted, paper_accepted, paper_rejected, invoice_created
            $table->string('subject');
            $table->longText('body'); // HTML body
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['conference_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
