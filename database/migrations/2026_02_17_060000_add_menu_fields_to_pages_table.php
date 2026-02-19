<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_menu')->default(false)->after('is_featured');
            $table->string('menu_type', 20)->nullable()->after('show_in_menu'); // main, child
            $table->unsignedBigInteger('menu_parent_id')->nullable()->after('menu_type');

            $table->foreign('menu_parent_id')->references('id')->on('menus')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['menu_parent_id']);
            $table->dropColumn(['show_in_menu', 'menu_type', 'menu_parent_id']);
        });
    }
};
