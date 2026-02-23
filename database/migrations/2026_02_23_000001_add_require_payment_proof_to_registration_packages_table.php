<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_packages', function (Blueprint $table) {
            $table->boolean('require_payment_proof')->default(false)->after('is_free');
        });
    }

    public function down(): void
    {
        Schema::table('registration_packages', function (Blueprint $table) {
            $table->dropColumn('require_payment_proof');
        });
    }
};
