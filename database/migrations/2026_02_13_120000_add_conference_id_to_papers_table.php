<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->foreignId('conference_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        // Auto-assign existing papers to the active conference (if any)
        $activeConference = \App\Models\Conference::where('is_active', true)->first();
        if ($activeConference) {
            \App\Models\Paper::whereNull('conference_id')->update(['conference_id' => $activeConference->id]);
        }
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropForeign(['conference_id']);
            $table->dropColumn('conference_id');
        });
    }
};
