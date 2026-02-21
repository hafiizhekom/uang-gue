<?php

namespace Database\Seeders;

use App\Models\MasterPeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MasterPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $users = User::all();

        foreach ($users as $user) {
            // Mulai dari bulan ini
            for ($i = 0; $i <= 3; $i++) {
                $date = now()->addMonths($i);

                MasterPeriod::factory()->create([
                    'user_id'    => $user->id,
                    'name'       => $date->translatedFormat('F Y'),
                    'start_date' => $date->copy()->startOfMonth()->toDateString(),
                    'end_date'   => $date->copy()->endOfMonth()->toDateString(),
                ]);
            }
        }
    }
}
