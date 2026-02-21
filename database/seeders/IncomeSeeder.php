<?php

namespace Database\Seeders;

use App\Models\Income;
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
            Income::factory(10)->create([
                'user_id' => $period->user_id,
                'master_period_id' => $period->id,
                'date' => $period->start_date, // Set tanggal di awal bulan periode tsb
            ]);
        }
    }
}
