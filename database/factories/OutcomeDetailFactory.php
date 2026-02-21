<?php

namespace Database\Factories;

use App\Models\MasterOutcomePayment;
use App\Models\Outcome;
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
            'master_outcome_payment_id' => MasterOutcomePayment::inRandomOrder()->first()->id,
            'note' => fake()->optional()->sentence(),
        ];
    }
}
