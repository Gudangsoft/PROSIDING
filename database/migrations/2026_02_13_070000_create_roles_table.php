<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // e.g. "Journal editor", "Reviewer"
            $table->string('slug')->unique(); // e.g. "journal-editor", "reviewer"
            $table->enum('permission_level', ['admin', 'manager', 'editor', 'assistant', 'reviewer', 'author'])
                  ->default('author');
            $table->boolean('can_submission')->default(false);
            $table->boolean('can_review')->default(false);
            $table->boolean('can_copyediting')->default(false);
            $table->boolean('can_production')->default(false);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
