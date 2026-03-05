<?php

namespace Database\Factories;

use App\Models\MasterIncomeType;
use App\Models\MasterPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
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
            'master_period_id' => MasterPeriod::factory(),
            'date' => fake()->date(),
            'title' => fake()->sentence(3),
            'amount' => fake()->numberBetween(1000000, 10000000),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
