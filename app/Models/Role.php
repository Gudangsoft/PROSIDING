<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'permission_level',
        'can_submission',
        'can_review',
        'can_copyediting',
        'can_production',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'can_submission' => 'boolean',
        'can_review' => 'boolean',
        'can_copyediting' => 'boolean',
        'can_production' => 'boolean',
    ];

    /**
     * Boot: auto-generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    /**
     * Users with this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }

    /**
     * Permission level labels for display
     */
    public static function permissionLevels(): array
    {
        return [
            'admin'     => 'Administrator',
            'manager'   => 'Journal Manager',
            'editor'    => 'Section Editor',
            'assistant' => 'Assistant',
            'reviewer'  => 'Reviewer',
            'author'    => 'Author',
        ];
    }

    /**
     * Get the permission level label
     */
    public function getPermissionLevelLabelAttribute(): string
    {
        return self::permissionLevels()[$this->permission_level] ?? ucfirst($this->permission_level);
    }

    /**
     * Get permissions as array
     */
    public function getPermissionsArrayAttribute(): array
    {
        return [
            'submission'  => $this->can_submission,
            'review'      => $this->can_review,
            'copyediting' => $this->can_copyediting,
            'production'  => $this->can_production,
        ];
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $column = "can_{$permission}";
        return $this->{$column} ?? false;
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
