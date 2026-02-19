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
        Schema::table('conferences', function (Blueprint $table) {
            $table->string('payment_bank_name')->nullable()->after('logo');
            $table->string('payment_bank_account')->nullable()->after('payment_bank_name');
            $table->string('payment_account_holder')->nullable()->after('payment_bank_account');
            $table->string('payment_contact_phone')->nullable()->after('payment_account_holder');
            $table->text('payment_instructions')->nullable()->after('payment_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn([
                'payment_bank_name',
                'payment_bank_account',
                'payment_account_holder',
                'payment_contact_phone',
                'payment_instructions',
            ]);
        });
    }
};
