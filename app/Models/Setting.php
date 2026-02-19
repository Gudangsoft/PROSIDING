<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group', 'key', 'value', 'type', 'label', 'description', 'options', 'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // ── Get value by key ──
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'number'  => (int) $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    // ── Set value by key ──
    public static function setValue(string $key, $value, ?string $group = 'general', ?string $type = 'text', ?string $label = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group, 'type' => $type, 'label' => $label ?? ucwords(str_replace('_', ' ', $key))]
        );
    }

    // ── Get all settings by group ──
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->orderBy('sort_order')
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    // ── Scope ──
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group)->orderBy('sort_order');
    }

    // ── GROUP CONSTANTS ──
    const GROUP_GENERAL = 'general';
    const GROUP_EMAIL = 'email';
    const GROUP_THEME = 'theme';

    const GROUPS = [
        'general' => 'Umum',
        'email' => 'Email',
        'theme' => 'Tema',
    ];
}
