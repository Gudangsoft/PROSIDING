<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new JSON column
        Schema::table('announcements', function (Blueprint $table) {
            $table->json('audience_new')->nullable()->after('priority');
        });
        
        // Convert existing audience enum values to JSON array
        DB::table('announcements')->get()->each(function ($announcement) {
            $audienceValue = $announcement->audience ?? 'all';
            DB::table('announcements')
                ->where('id', $announcement->id)
                ->update(['audience_new' => json_encode([$audienceValue])]);
        });
        
        // Drop old column and rename new one
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('audience');
        });
        
        Schema::table('announcements', function (Blueprint $table) {
            $table->renameColumn('audience_new', 'audience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add new enum column
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('audience_old', ['all', 'author', 'reviewer', 'editor', 'admin', 'participant'])
                ->default('all')
                ->after('priority');
        });
        
        // Get first value from array and convert back to enum
        DB::table('announcements')->get()->each(function ($announcement) {
            if ($announcement->audience) {
                $audienceArray = json_decode($announcement->audience, true);
                $firstValue = is_array($audienceArray) && count($audienceArray) > 0 
                    ? $audienceArray[0] 
                    : 'all';
                    
                DB::table('announcements')
                    ->where('id', $announcement->id)
                    ->update(['audience_old' => $firstValue]);
            }
        });
        
        // Drop JSON column and rename old one back
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('audience');
        });
        
        Schema::table('announcements', function (Blueprint $table) {
            $table->renameColumn('audience_old', 'audience');
        });
    }
};
