<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OutcomeDetail>
 */
class OutcomeDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'title' => fake()->words(2, true),
            'amount' => fake()->numberBetween(10000, 500000),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
