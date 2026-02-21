<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterPeriod>
 */
class MasterPeriodFactory extends Factory
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
            'name' => $this->faker->monthName() . ' ' . $this->faker->year(),
            'start_date' => $this->faker->dateTimeThisYear()->format('Y-m-01'),
            'end_date' => $this->faker->dateTimeThisYear()->format('Y-m-t'),
        ];
    }
}
