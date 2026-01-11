<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Subscriptions;
use App\Models\SubscriptionPlans;
use App\Models\Payments;
use App\Models\UserPrograms;
use App\Models\Programs;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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
