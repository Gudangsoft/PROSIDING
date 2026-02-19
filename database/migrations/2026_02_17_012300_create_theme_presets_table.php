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
        Schema::create('theme_presets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);

            // Colors
            $table->string('primary_color', 20)->default('#2563eb');
            $table->string('secondary_color', 20)->default('#4f46e5');
            $table->string('accent_color', 20)->default('#0891b2');
            $table->string('sidebar_bg', 20)->default('#ffffff');
            $table->string('sidebar_text', 20)->default('#374151');
            $table->string('header_bg', 20)->default('#ffffff');
            $table->string('body_bg', 20)->default('#f3f4f6');

            // Public-facing colors
            $table->string('nav_bg', 20)->default('#ffffff');
            $table->string('nav_text', 20)->default('#374151');
            $table->string('hero_bg', 20)->default('#0d9488');
            $table->string('hero_text', 20)->default('#ffffff');
            $table->string('footer_bg', 20)->default('#111827');
            $table->string('footer_text_color', 20)->default('#9ca3af');
            $table->string('link_color', 20)->default('#0d9488');
            $table->string('link_hover_color', 20)->default('#0f766e');
            $table->string('button_bg', 20)->default('#0d9488');
            $table->string('button_text', 20)->default('#ffffff');
            $table->string('card_bg', 20)->default('#ffffff');
            $table->string('card_border', 20)->default('#e5e7eb');
            $table->string('section_alt_bg', 20)->default('#f9fafb');

            // Typography & UI
            $table->string('font_family', 50)->default('Inter');
            $table->string('heading_font', 50)->default('Inter');
            $table->string('font_size_base', 10)->default('16');
            $table->string('border_radius', 10)->default('8');
            $table->string('shadow_style', 20)->default('sm');

            // Layout options
            $table->string('navbar_style', 30)->default('glass');
            $table->string('hero_style', 30)->default('gradient');
            $table->string('footer_style', 30)->default('dark');
            $table->string('card_style', 30)->default('bordered');
            $table->string('container_width', 10)->default('6xl');

            // Custom
            $table->text('custom_css')->nullable();
            $table->string('login_bg_image')->nullable();
            $table->json('extra')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_presets');
    }
};
