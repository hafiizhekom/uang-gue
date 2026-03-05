<?php

namespace Database\Factories;

use App\Models\MasterPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Outcome>
 */
class OutcomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'master_period_id' => MasterPeriod::factory(),
            'date' => fake()->date(),
            'title' => fake()->words(3, true),
            'amount' => fake()->numberBetween(50000, 5000000),
            'has_detail' => false, 
            'note' => fake()->optional()->sentence(),
        ];
    }
}
