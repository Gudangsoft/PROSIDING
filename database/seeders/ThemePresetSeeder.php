<?php

namespace Database\Seeders;

use App\Models\ThemePreset;
use Illuminate\Database\Seeder;

class ThemePresetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Blue — matches original admin design
        ThemePreset::updateOrCreate(
            ['slug' => 'default-blue'],
            [
                'name' => 'Default Blue',
                'description' => 'Tema bawaan biru profesional',
                'is_active' => true,
                'is_default' => true,
                'linked_template' => 'default-blue',
                // Admin colors
                'primary_color' => '#2563eb',
                'secondary_color' => '#4f46e5',
                'accent_color' => '#0891b2',
                'sidebar_bg' => '#ffffff',
                'sidebar_text' => '#374151',
                'header_bg' => '#ffffff',
                'body_bg' => '#f3f4f6',
                // Public colors
                'nav_bg' => '#ffffff',
                'nav_text' => '#374151',
                'hero_bg' => '#1e40af',
                'hero_text' => '#ffffff',
                'footer_bg' => '#111827',
                'footer_text_color' => '#9ca3af',
                'link_color' => '#2563eb',
                'link_hover_color' => '#1d4ed8',
                'button_bg' => '#2563eb',
                'button_text' => '#ffffff',
                'card_bg' => '#ffffff',
                'card_border' => '#e5e7eb',
                'section_alt_bg' => '#f9fafb',
                // Typography
                'font_family' => 'Inter',
                'heading_font' => 'Inter',
                'font_size_base' => '16',
                'border_radius' => '8',
                'shadow_style' => 'sm',
                // Layout
                'navbar_style' => 'glass',
                'hero_style' => 'gradient',
                'footer_style' => 'dark',
                'card_style' => 'bordered',
                'container_width' => '6xl',
                'custom_css' => '',
            ]
        );

        // 2. Health Teal — matches "oke" template
        ThemePreset::updateOrCreate(
            ['slug' => 'health-teal'],
            [
                'name' => 'Health Teal',
                'description' => 'Tema teal/emerald untuk kesehatan',
                'is_active' => false,
                'is_default' => false,
                'linked_template' => 'health-teal',
                // Admin colors
                'primary_color' => '#0d9488',
                'secondary_color' => '#059669',
                'accent_color' => '#0891b2',
                'sidebar_bg' => '#f0fdfa',
                'sidebar_text' => '#134e4a',
                'header_bg' => '#ffffff',
                'body_bg' => '#f0fdfa',
                // Public colors
                'nav_bg' => '#ffffff',
                'nav_text' => '#134e4a',
                'hero_bg' => '#0d9488',
                'hero_text' => '#ffffff',
                'footer_bg' => '#134e4a',
                'footer_text_color' => '#99f6e4',
                'link_color' => '#0d9488',
                'link_hover_color' => '#0f766e',
                'button_bg' => '#0d9488',
                'button_text' => '#ffffff',
                'card_bg' => '#ffffff',
                'card_border' => '#ccfbf1',
                'section_alt_bg' => '#f0fdfa',
                // Typography
                'font_family' => 'Plus Jakarta Sans',
                'heading_font' => 'Plus Jakarta Sans',
                'font_size_base' => '16',
                'border_radius' => '12',
                'shadow_style' => 'md',
                // Layout
                'navbar_style' => 'solid',
                'hero_style' => 'gradient',
                'footer_style' => 'dark',
                'card_style' => 'shadow',
                'container_width' => '7xl',
                'custom_css' => '',
            ]
        );

        // 3. Dark Elegant
        ThemePreset::updateOrCreate(
            ['slug' => 'dark-elegant'],
            [
                'name' => 'Dark Elegant',
                'description' => 'Tema gelap elegan dengan aksen emas',
                'is_active' => false,
                'is_default' => false,
                'linked_template' => 'dark-elegant',
                // Admin colors
                'primary_color' => '#f59e0b',
                'secondary_color' => '#d97706',
                'accent_color' => '#f472b6',
                'sidebar_bg' => '#1f2937',
                'sidebar_text' => '#e5e7eb',
                'header_bg' => '#111827',
                'body_bg' => '#030712',
                // Public colors
                'nav_bg' => '#111827',
                'nav_text' => '#e5e7eb',
                'hero_bg' => '#1f2937',
                'hero_text' => '#fbbf24',
                'footer_bg' => '#030712',
                'footer_text_color' => '#6b7280',
                'link_color' => '#fbbf24',
                'link_hover_color' => '#f59e0b',
                'button_bg' => '#f59e0b',
                'button_text' => '#111827',
                'card_bg' => '#1f2937',
                'card_border' => '#374151',
                'section_alt_bg' => '#111827',
                // Typography
                'font_family' => 'DM Sans',
                'heading_font' => 'Montserrat',
                'font_size_base' => '16',
                'border_radius' => '8',
                'shadow_style' => 'lg',
                // Layout
                'navbar_style' => 'solid',
                'hero_style' => 'minimal',
                'footer_style' => 'minimal',
                'card_style' => 'elevated',
                'container_width' => '6xl',
                'custom_css' => '',
            ]
        );
    }
}
