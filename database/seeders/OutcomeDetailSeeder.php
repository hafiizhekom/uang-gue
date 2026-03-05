<?php

namespace Database\Seeders;

use App\Models\MasterPeriod;
use App\Models\Outcome;
use App\Models\OutcomeDetail;
use Illuminate\Database\Seeder;

class OutcomeDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Outcome $outcome, MasterPeriod $period): void
    {
        //
        // Pastikan parameter ada (biar gak error kalau dipanggil salah)
        if (!$outcome || !$period) return;

        $paymentIds  = \App\Models\MasterPayment::where('user_id', $period->user_id)->pluck('id');
        $tagIds = \App\Models\MasterOutcomeDetailTag::where('user_id', $period->user_id)->pluck('id');

        $details = OutcomeDetail::factory(3)->create([
            'outcome_id' => $outcome->id,
            'date' => fake()->dateTimeBetween($period->start_date, $period->end_date)->format('Y-m-d'),
            'master_payment_id' => fn() => $paymentIds->random(),
        ]);

        if ($tagIds->isNotEmpty()) {
            foreach ($details as $detail) {
                $detail->tags()->attach(
                    $tagIds->random(rand(1, min(2, $tagIds->count())))
                );
            }
        }
    }
}
