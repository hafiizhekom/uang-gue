<?php

namespace Database\Seeders;

use App\Models\Outcome;
use App\Models\User;
use Database\Seeders\IncomeSeeder;
use Database\Seeders\MasterIncomeTypeSeeder;
use Database\Seeders\MasterOutcomeCategorySeeder;
use Database\Seeders\MasterOutcomeDetailTagSeeder;
use Database\Seeders\MasterOutcomeTypeSeeder;
use Database\Seeders\MasterPaymentSeeder;
use Database\Seeders\MasterPeriodSeeder;
use Database\Seeders\OutcomeSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $myUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            UserSeeder::class, //Bikin 10 user
            MasterIncomeTypeSeeder::class, //Get semua user, buat 3 jenis income untuk masing2 user
            MasterOutcomeCategorySeeder::class, //Get semua user, buat 9 jenis outcome category untuk masing2 user
            MasterPaymentSeeder::class, //Get semua user, buat 10 jenis payment untuk masing2 user
            MasterOutcomeTypeSeeder::class, //Get semua user, buat 3 jenis outcome type untuk masing2 user
            MasterOutcomeDetailTagSeeder::class, //Get semua user, buat 4 jenis outcome detail tag untuk masing2 user
            MasterPeriodSeeder::class, //Get semua user, buat 4 period (bulan ini, bulan depan, dst) untuk masing2 user
            IncomeSeeder::class, //Get semua period, buat 10 income untuk masing2 period dengan type dan payment random dari user yang punya period tsb
            OutcomeSeeder::class, //Get semua period, buat 10 outcome untuk masing2 period dengan category, type, payment random dari user yang punya period tsb. 5 outcome punya detail, 5 outcome ga punya detail
        ]);

        // Update amount di outcome yang punya detail supaya sesuai dengan jumlah detailnya
        Outcome::where('has_detail', true)->with('details')->get()->each(function ($outcome) {
            $outcome->update([
                'amount' => $outcome->details->sum('amount')
            ]);
        });
    }
}
