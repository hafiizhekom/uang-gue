<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\MasterIncomeType;
use App\Models\MasterPayment;
use App\Models\MasterPeriod;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $allPeriods = MasterPeriod::all();

        foreach ($allPeriods as $period) {
            $typeIds = MasterIncomeType::where('user_id', $period->user_id)
            ->pluck('id');
            $paymentIds = MasterPayment::where('user_id', $period->user_id)
            ->pluck('id');

            Income::factory(10)->create([
                'user_id' => $period->user_id,
                'master_period_id' => $period->id,
                'date' => $period->start_date,
                // Pakai Closure supaya tiap row manggil random() lagi
                'master_income_type_id' => fn() => $typeIds->random(),
                'master_payment_id' => fn() => $paymentIds->random(),
            ]);
        }
    }
}
