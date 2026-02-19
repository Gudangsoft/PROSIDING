<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable()->after('name');
            $table->string('country')->nullable()->after('institution');
            $table->string('participation')->nullable()->after('country');
            $table->string('research_interest')->nullable()->after('participation');
            $table->text('other_info')->nullable()->after('research_interest');
            $table->string('proof_of_payment')->nullable()->after('other_info');
            $table->string('signature')->nullable()->after('proof_of_payment');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender', 'country', 'participation',
                'research_interest', 'other_info',
                'proof_of_payment', 'signature',
            ]);
        });
    }
};
