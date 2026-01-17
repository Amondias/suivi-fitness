<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Subscriptions;
use App\Models\Payments;
use App\Models\UserPrograms;
use App\Models\Programs;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscriptions::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'user_id');
    }

    public function userPrograms(): HasMany
    {
        return $this->hasMany(UserPrograms::class, 'user_id');
    }

    public function coachedPrograms(): HasMany
    {
        return $this->hasMany(Programs::class, 'coach_id');
    }
}
