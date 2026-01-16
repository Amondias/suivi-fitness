<?php

namespace Database\Factories;

use App\Models\SubscriptionPlans;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlansFactory extends Factory
{
    protected $model = SubscriptionPlans::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Plan',
            'description' => $this->faker->sentence(),
            'duration_months' => $this->faker->numberBetween(1, 12),
            'price' => $this->faker->numberBetween(5000, 500000),
            'features' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
