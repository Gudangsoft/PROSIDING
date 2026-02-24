<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->string('camera_ready_path')->nullable()->after('ojs_submitted_at');
            $table->timestamp('camera_ready_submitted_at')->nullable()->after('camera_ready_path');
            $table->enum('camera_ready_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('camera_ready_submitted_at');
            $table->text('camera_ready_notes')->nullable()->after('camera_ready_status');
            $table->string('acceptance_letter_path')->nullable()->after('camera_ready_notes');
            $table->timestamp('acceptance_letter_sent_at')->nullable()->after('acceptance_letter_path');
        });
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn([
                'camera_ready_path', 'camera_ready_submitted_at',
                'camera_ready_status', 'camera_ready_notes',
                'acceptance_letter_path', 'acceptance_letter_sent_at',
            ]);
        });
    }
};
