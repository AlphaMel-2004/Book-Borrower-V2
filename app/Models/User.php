<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CanResetPassword, Authorizable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function getIsAdminAttribute(): bool
    {
        return (bool) $this->attributes['is_admin'];
    }

    /**
     * Alternative method to check admin status
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function fines(): HasManyThrough
    {
        return $this->hasManyThrough(Fine::class, Borrowing::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function processedRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'processed_by');
    }

}
