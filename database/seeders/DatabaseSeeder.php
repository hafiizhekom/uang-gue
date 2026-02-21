<?php

namespace Database\Seeders;

use App\Models\MasterOutcomeDetailTag;
use App\Models\Outcome;
use App\Models\OutcomeDetail;
use App\Models\User;
use Database\Seeders\IncomeSeeder;
use Database\Seeders\MasterIncomeTypeSeeder;
use Database\Seeders\MasterOutcomeCategorySeeder;
use Database\Seeders\MasterOutcomeDetailTagSeeder;
use Database\Seeders\MasterOutcomeHutangSeeder;
use Database\Seeders\MasterOutcomePaymentSeeder;
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
            UserSeeder::class,
            MasterIncomeTypeSeeder::class,
            MasterOutcomeCategorySeeder::class,
            MasterOutcomePaymentSeeder::class,
            MasterOutcomeHutangSeeder::class,
            MasterOutcomeDetailTagSeeder::class,
            MasterPeriodSeeder::class,
            IncomeSeeder::class,
            OutcomeSeeder::class,
        ]);

        $allTags = MasterOutcomeDetailTag::all();
        OutcomeDetail::all()->each(function ($detail) use ($allTags) {
            $detail->tags()->attach(
                $allTags->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        Outcome::where('has_detail', true)->with('details')->get()->each(function ($outcome) {
            $outcome->update([
                'amount' => $outcome->details->sum('amount')
            ]);
        });
    }
}
