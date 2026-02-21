<?php

namespace Database\Seeders;

use App\Models\MasterPeriod;
use App\Models\Outcome;
use Database\Seeders\OutcomeDetailSeeder;
use Illuminate\Database\Seeder;

class OutcomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $allPeriods = MasterPeriod::all();

        foreach ($allPeriods as $period) {
            // Outcome Tanpa Detail
            Outcome::factory(3)->create([
                'user_id' => $period->user_id,
                'master_period_id' => $period->id,
                'date' => $period->start_date,
                'has_detail' => false,
            ]);

            $outcomesWithDetail = Outcome::factory(2)->create([
                'user_id' => $period->user_id,
                'master_period_id' => $period->id,
                'date' => $period->start_date,
                'has_detail' => true,
            ]);

            foreach ($outcomesWithDetail as $outcome) {
                $this->callWith(OutcomeDetailSeeder::class, [
                    'outcome' => $outcome,
                    'period'  => $period
                ]);
            }
        }
    }
}
