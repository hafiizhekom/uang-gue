<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketAttachment>
 */
class TicketAttachmentFactory extends Factory
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
            // 'ticket_id' => \App\Models\Ticket::factory(),
            'ticket_id' => Ticket::inRandomOrder()->value('id'),
            'file_name' => $this->faker->word() . '.txt',
            'file_path' => '/attachments/' . $this->faker->word() . '.txt',
            'file_size' => $this->faker->numberBetween(100, 5000),
            // 'uploaded_by' => \App\Models\User::factory(),
            'uploaded_by' => User::inRandomOrder()->value('id'),
            'uploaded_at' => time(),
        ];
    }
}
