<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── WhatsApp API Settings ────────────────────────────────────
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('default'); // profile name
            $table->enum('provider', ['fonnte', 'wablas', 'custom'])->default('fonnte');
            $table->string('api_key')->nullable();
            $table->string('api_url')->nullable(); // custom endpoint for wablas/custom
            $table->string('device_id')->nullable(); // wablas device id
            $table->string('sender_number')->nullable(); // display number
            $table->boolean('is_active')->default(false);
            $table->string('test_number')->nullable();

            // Message templates
            $table->text('tpl_payment_reminder')->nullable();
            $table->text('tpl_payment_verified')->nullable();
            $table->text('tpl_paper_accepted')->nullable();
            $table->text('tpl_paper_rejected')->nullable();
            $table->text('tpl_paper_revision')->nullable();
            $table->text('tpl_review_assigned')->nullable();
            $table->text('tpl_abstract_approved')->nullable();
            $table->text('tpl_abstract_rejected')->nullable();

            // Feature toggles
            $table->boolean('notify_payment_reminder')->default(true);
            $table->boolean('notify_payment_verified')->default(true);
            $table->boolean('notify_paper_status')->default(true);
            $table->boolean('notify_review_assigned')->default(true);
            $table->boolean('notify_abstract_status')->default(true);

            $table->timestamps();
        });

        // ─── WhatsApp Send Logs ───────────────────────────────────────
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->nullable()->constrained('whatsapp_settings')->nullOnDelete();
            $table->string('to'); // destination number
            $table->string('recipient_name')->nullable();
            $table->string('type')->default('manual'); // payment_reminder, paper_status, test, manual, etc.
            $table->text('message');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->text('api_response')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // recipient user
            $table->foreignId('paper_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
        Schema::dropIfExists('whatsapp_settings');
    }
};
