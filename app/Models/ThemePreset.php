<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ThemePreset extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'extra' => 'array',
        'sections_config' => 'array',
    ];

    // ── Color fields ──
    public const COLOR_FIELDS = [
        // Admin
        'primary_color', 'secondary_color', 'accent_color',
        'sidebar_bg', 'sidebar_text', 'header_bg', 'body_bg',
        // Public
        'nav_bg', 'nav_text', 'hero_bg', 'hero_text',
        'footer_bg', 'footer_text_color', 'link_color', 'link_hover_color',
        'button_bg', 'button_text', 'card_bg', 'card_border', 'section_alt_bg',
    ];

    // ── Typography fields ──
    public const TYPO_FIELDS = [
        'font_family', 'heading_font', 'font_size_base', 'border_radius', 'shadow_style',
    ];

    // ── Layout fields ──
    public const LAYOUT_FIELDS = [
        'navbar_style', 'hero_style', 'footer_style', 'card_style', 'container_width',
    ];

    // ── Option lists ──
    public const FONT_OPTIONS = [
        'Inter' => 'Inter',
        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
        'Poppins' => 'Poppins',
        'Roboto' => 'Roboto',
        'Open Sans' => 'Open Sans',
        'Nunito' => 'Nunito',
        'Montserrat' => 'Montserrat',
        'Lato' => 'Lato',
        'DM Sans' => 'DM Sans',
        'Source Sans Pro' => 'Source Sans Pro',
    ];

    public const BORDER_RADIUS_OPTIONS = [
        '0' => 'Tajam (0px)',
        '4' => 'Sedikit (4px)',
        '8' => 'Sedang (8px)',
        '12' => 'Bulat (12px)',
        '16' => 'Sangat Bulat (16px)',
        '9999' => 'Pill / Full',
    ];

    public const SHADOW_OPTIONS = [
        'none' => 'Tanpa Shadow',
        'sm' => 'Kecil',
        'md' => 'Sedang',
        'lg' => 'Besar',
        'xl' => 'Ekstra Besar',
    ];

    public const NAVBAR_STYLES = [
        'glass' => 'Glassmorphism (transparan blur)',
        'solid' => 'Solid (warna penuh)',
        'transparent' => 'Transparan',
        'bordered' => 'Border bawah',
    ];

    public const HERO_STYLES = [
        'gradient' => 'Gradient',
        'image' => 'Background Image',
        'minimal' => 'Minimal (teks saja)',
        'split' => 'Split (teks + gambar)',
    ];

    public const FOOTER_STYLES = [
        'dark' => 'Gelap',
        'light' => 'Terang',
        'colored' => 'Berwarna (primary)',
        'minimal' => 'Minimal',
    ];

    public const CARD_STYLES = [
        'bordered' => 'Bordered',
        'shadow' => 'Shadow',
        'flat' => 'Flat',
        'elevated' => 'Elevated',
    ];

    public const CONTAINER_WIDTH_OPTIONS = [
        '5xl' => 'Sempit (max-w-5xl)',
        '6xl' => 'Sedang (max-w-6xl)',
        '7xl' => 'Lebar (max-w-7xl)',
        'full' => 'Full Width',
    ];

    // ── Section definitions (id => label) ──
    public const SECTIONS = [
        'hero'          => 'Hero / Slider',
        'info_cards'    => 'Info Cards (Tanggal Penting)',
        'about'         => 'Tentang Konferensi',
        'speakers'      => 'Keynote Speakers',
        'committees'    => 'Komite / Panitia',
        'news'          => 'Berita & Pengumuman',
        'registration'  => 'Registrasi / Paket Harga',
        'journals'      => 'Jurnal Publikasi',
        'cta'           => 'Call to Action',
    ];

    // ── Default sections config ──
    public const DEFAULT_SECTIONS_CONFIG = [
        ['id' => 'hero',         'visible' => true, 'order' => 1],
        ['id' => 'info_cards',   'visible' => true, 'order' => 2],
        ['id' => 'about',        'visible' => true, 'order' => 3],
        ['id' => 'speakers',     'visible' => true, 'order' => 4],
        ['id' => 'committees',   'visible' => true, 'order' => 5],
        ['id' => 'news',         'visible' => true, 'order' => 6],
        ['id' => 'registration', 'visible' => true, 'order' => 7],
        ['id' => 'journals',     'visible' => true, 'order' => 8],
        ['id' => 'cta',          'visible' => true, 'order' => 9],
    ];

    // ── Defaults ──
    public const DEFAULTS = [
        'primary_color' => '#2563eb',
        'secondary_color' => '#4f46e5',
        'accent_color' => '#0891b2',
        'sidebar_bg' => '#ffffff',
        'sidebar_text' => '#374151',
        'header_bg' => '#ffffff',
        'body_bg' => '#f3f4f6',
        'nav_bg' => '#ffffff',
        'nav_text' => '#374151',
        'hero_bg' => '#0d9488',
        'hero_text' => '#ffffff',
        'footer_bg' => '#111827',
        'footer_text_color' => '#9ca3af',
        'link_color' => '#0d9488',
        'link_hover_color' => '#0f766e',
        'button_bg' => '#0d9488',
        'button_text' => '#ffffff',
        'card_bg' => '#ffffff',
        'card_border' => '#e5e7eb',
        'section_alt_bg' => '#f9fafb',
        'font_family' => 'Inter',
        'heading_font' => 'Inter',
        'font_size_base' => '16',
        'border_radius' => '8',
        'shadow_style' => 'sm',
        'navbar_style' => 'glass',
        'hero_style' => 'gradient',
        'footer_style' => 'dark',
        'card_style' => 'bordered',
        'container_width' => '6xl',
        'custom_css' => '',
        'login_bg_image' => null,
    ];

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Helpers ──
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get the sections config, merged with defaults for any missing sections.
     */
    public function getSectionsConfig(): array
    {
        $config = $this->sections_config;
        if (empty($config)) {
            return self::DEFAULT_SECTIONS_CONFIG;
        }

        // Ensure all sections exist (in case new ones were added)
        $existingIds = array_column($config, 'id');
        $maxOrder = max(array_column($config, 'order'));
        foreach (self::SECTIONS as $id => $label) {
            if (!in_array($id, $existingIds)) {
                $config[] = ['id' => $id, 'visible' => true, 'order' => ++$maxOrder];
            }
        }

        // Sort by order
        usort($config, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
        return $config;
    }

    /**
     * Get ordered visible sections for template rendering.
     */
    public function getVisibleSections(): array
    {
        return array_values(array_filter(
            $this->getSectionsConfig(),
            fn($s) => $s['visible'] ?? true
        ));
    }

    /**
     * Get active preset or return defaults as an array.
     */
    public static function activeOrDefaults(): array
    {
        $active = static::getActive();
        if ($active) {
            return $active->toArray();
        }
        return self::DEFAULTS;
    }

    /**
     * Generate CSS custom properties from this preset.
     */
    public function toCssVariables(): string
    {
        $vars = [];
        foreach (self::COLOR_FIELDS as $field) {
            $cssName = '--theme-' . str_replace('_', '-', $field);
            $vars[] = "{$cssName}: {$this->$field};";
        }
        $vars[] = "--theme-font-family: '{$this->font_family}', system-ui, sans-serif;";
        $vars[] = "--theme-heading-font: '{$this->heading_font}', system-ui, sans-serif;";
        $vars[] = "--theme-font-size-base: {$this->font_size_base}px;";
        $vars[] = "--theme-border-radius: {$this->border_radius}px;";
        $vars[] = "--theme-shadow: " . $this->getShadowValue() . ";";
        $vars[] = "--theme-container-width: " . $this->getContainerWidth() . ";";

        return ":root {\n  " . implode("\n  ", $vars) . "\n}";
    }

    /**
     * Generate CSS variables from defaults (static).
     */
    public static function defaultCssVariables(): string
    {
        $d = self::DEFAULTS;
        $vars = [];
        foreach (self::COLOR_FIELDS as $field) {
            $cssName = '--theme-' . str_replace('_', '-', $field);
            $vars[] = "{$cssName}: {$d[$field]};";
        }
        $vars[] = "--theme-font-family: '{$d['font_family']}', system-ui, sans-serif;";
        $vars[] = "--theme-heading-font: '{$d['heading_font']}', system-ui, sans-serif;";
        $vars[] = "--theme-font-size-base: {$d['font_size_base']}px;";
        $vars[] = "--theme-border-radius: {$d['border_radius']}px;";
        $vars[] = "--theme-shadow: 0 1px 2px rgba(0,0,0,0.05);";
        $vars[] = "--theme-container-width: 72rem;";

        return ":root {\n  " . implode("\n  ", $vars) . "\n}";
    }

    protected function getShadowValue(): string
    {
        return match ($this->shadow_style) {
            'none' => 'none',
            'sm' => '0 1px 2px rgba(0,0,0,0.05)',
            'md' => '0 4px 6px -1px rgba(0,0,0,0.1)',
            'lg' => '0 10px 15px -3px rgba(0,0,0,0.1)',
            'xl' => '0 20px 25px -5px rgba(0,0,0,0.1)',
            default => '0 1px 2px rgba(0,0,0,0.05)',
        };
    }

    protected function getContainerWidth(): string
    {
        return match ($this->container_width) {
            '5xl' => '64rem',
            '6xl' => '72rem',
            '7xl' => '80rem',
            'full' => '100%',
            default => '72rem',
        };
    }

    protected static function booted(): void
    {
        static::creating(function ($preset) {
            if (empty($preset->slug)) {
                $preset->slug = Str::slug($preset->name);
            }
        });

        // Ensure only one active preset
        static::saving(function ($preset) {
            if ($preset->is_active) {
                static::where('id', '!=', $preset->id ?? 0)->update(['is_active' => false]);
            }
        });
    }
}
