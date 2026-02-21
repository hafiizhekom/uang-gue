<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            // 'category_id' => \App\Models\TicketCategory::factory(),
            'category_id' => \App\Models\TicketCategory::inRandomOrder()->value('id'),
            // 'assigned_to' => \App\Models\User::factory(),
            'assigned_to' => \App\Models\User::inRandomOrder()->value('id'),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'created_by' => \App\Models\User::inRandomOrder()->value('id'),
        ];
    }
}
