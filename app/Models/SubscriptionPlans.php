<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Subscriptions;

class SubscriptionPlans extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'description',
        'duration_months',
        'price',
        'features',
        'is_active',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscriptions::class, 'plan_id');
    }
}
