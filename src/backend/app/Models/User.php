<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function getAuthPassword(): string
{
    return $this->password_hash;
}

protected $fillable = [
    'name', 'email', 'password_hash', 'phone', 'role',
];

protected $hidden = [
    'password_hash', 'remember_token',
];      

    
}
