<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter users.role enum to add 'participant'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('author','reviewer','editor','admin','participant') DEFAULT 'author'");

        // Alter announcements.audience enum to add 'participant'
        DB::statement("ALTER TABLE announcements MODIFY COLUMN audience ENUM('all','author','reviewer','editor','admin','participant') DEFAULT 'all'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('author','reviewer','editor','admin') DEFAULT 'author'");
        DB::statement("ALTER TABLE announcements MODIFY COLUMN audience ENUM('all','author','reviewer','editor','admin') DEFAULT 'all'");
    }
};
