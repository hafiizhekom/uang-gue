<?php

namespace Database\Factories;

use App\Models\MasterOutcomeCategory;
use App\Models\MasterOutcomeHutang;
use App\Models\MasterOutcomePayment;
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
            'has_detail' => false, // 30% kemungkinan punya detail
            'master_outcome_category_id' => MasterOutcomeCategory::inRandomOrder()->first()->id,
            'master_outcome_hutang_id' => fake()->boolean(20) ? MasterOutcomeHutang::inRandomOrder()->first()->id : null,
            'master_outcome_payment_id' => MasterOutcomePayment::inRandomOrder()->first()->id,
        ];
    }
}
