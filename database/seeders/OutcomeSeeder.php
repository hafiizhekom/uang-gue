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
            $categoryIds = \App\Models\MasterOutcomeCategory::where('user_id', $period->user_id)->pluck('id');
            $paymentIds  = \App\Models\MasterPayment::where('user_id', $period->user_id)->pluck('id');
            $typeIds   = \App\Models\MasterOutcomeType::where('user_id', $period->user_id)->pluck('id');

            $baseData = [
                'user_id'          => $period->user_id,
                'master_period_id' => $period->id,
                'date'             => $period->start_date,
                'master_outcome_category_id' => fn() => $categoryIds->random(),
                'master_outcome_type_id'   => fn() => fake()->boolean(20) && $typeIds->isNotEmpty() 
                                                ? $typeIds->random() 
                                                : null,
            ];
            
            // Outcome Tanpa Detail
            Outcome::factory(3)->create(array_merge($baseData, [
                'has_detail'        => false,
                'master_payment_id' => fn() => $paymentIds->random(), // Bayar langsung di sini
            ]));

            // Outcome Dengan Detail
            $outcomesWithDetail = Outcome::factory(2)->create(array_merge($baseData, [
                'has_detail'        => true,
                'master_payment_id' => null,
            ]));

            foreach ($outcomesWithDetail as $outcome) {
                $this->callWith(OutcomeDetailSeeder::class, [
                    'outcome' => $outcome,
                    'period'  => $period
                ]);
            }
        }
    }
}
