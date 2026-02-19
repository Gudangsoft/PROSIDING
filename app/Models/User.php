<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'photo', 'email', 'password', 'role', 'gender', 'institution', 'country',
        'participation', 'research_interest', 'phone', 'other_info',
        'proof_of_payment', 'signature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isEditor(): bool { return $this->role === 'editor'; }
    public function isReviewer(): bool { return $this->role === 'reviewer'; }
    public function isAuthor(): bool { return $this->role === 'author'; }
    public function isParticipant(): bool { return $this->role === 'participant'; }
    public function isAdminOrEditor(): bool { return in_array($this->role, ['admin', 'editor']); }

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    /**
     * Check if user has a specific role (by slug)
     */
    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    /**
     * Check if user has a specific stage permission through any of their roles
     */
    public function hasStagePermission(string $stage): bool
    {
        $column = "can_{$stage}";
        return $this->roles()->where($column, true)->exists();
    }

    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('is_read', false)->latest();
    }
}
