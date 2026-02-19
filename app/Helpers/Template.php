<?php

namespace App\Helpers;

use App\Models\Setting;

class Template
{
    /**
     * Get the active template name from settings.
     */
    public static function active(): string
    {
        return Setting::getValue('active_template', 'default');
    }

    /**
     * Resolve a view path within the active template.
     * Example: Template::view('welcome') → 'templates.default.welcome'
     */
    public static function view(string $view): string
    {
        $template = static::active();
        $path = "templates.{$template}.{$view}";

        // Fallback to default template if view doesn't exist in active template
        if (!view()->exists($path) && $template !== 'default') {
            $path = "templates.default.{$view}";
        }

        return $path;
    }

    /**
     * List all available templates (folder names inside resources/views/templates/).
     */
    public static function available(): array
    {
        $path = resource_path('views/templates');
        if (!is_dir($path)) return ['default'];

        $dirs = array_filter(glob($path . '/*'), 'is_dir');
        return array_map('basename', $dirs);
    }
}
