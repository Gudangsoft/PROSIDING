<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->string('wa_group_pemakalah', 500)->nullable()->after('payment_methods');
            $table->string('wa_group_non_pemakalah', 500)->nullable()->after('wa_group_pemakalah');
            $table->string('wa_group_reviewer', 500)->nullable()->after('wa_group_non_pemakalah');
            $table->string('wa_group_editor', 500)->nullable()->after('wa_group_reviewer');
        });
    }

    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn(['wa_group_pemakalah', 'wa_group_non_pemakalah', 'wa_group_reviewer', 'wa_group_editor']);
        });
    }
};
