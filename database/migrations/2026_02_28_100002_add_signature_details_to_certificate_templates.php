<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            // Signature 1
            $table->string('signature1_name')->nullable()->after('signature_image');
            $table->string('signature1_position')->nullable()->after('signature1_name');
            // Signature 2
            $table->string('signature2_image')->nullable()->after('signature1_position');
            $table->string('signature2_name')->nullable()->after('signature2_image');
            $table->string('signature2_position')->nullable()->after('signature2_name');
        });
    }

    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn([
                'signature1_name', 'signature1_position',
                'signature2_image', 'signature2_name', 'signature2_position',
            ]);
        });
    }
};
