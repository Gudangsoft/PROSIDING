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
        // Add 'treasurer' role to the ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('author','reviewer','editor','admin','participant','treasurer') DEFAULT 'author'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'treasurer' role from the ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('author','reviewer','editor','admin','participant') DEFAULT 'author'");
    }
};
