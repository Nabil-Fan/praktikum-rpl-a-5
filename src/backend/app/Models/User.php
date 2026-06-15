<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'username', 'password_hash', 'phone', 'role',
    ];

    protected $hidden = [
        'password_hash', 'remember_token',
    ];

    protected $casts = [
        'role'       => UserRole::class,
        'deleted_at' => 'datetime',
    ];

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function isUser(): bool { return $this->role === UserRole::USER; }
    public function isMerchant(): bool { return $this->role === UserRole::MERCHANT; }
    public function isAdmin(): bool { return $this->role === UserRole::ADMIN; }
    public function isDeleted(): bool { return $this->deleted_at !== null; }

    public function merchantProfile(): HasOne
    {
        return $this->hasOne(MerchantProfile::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}