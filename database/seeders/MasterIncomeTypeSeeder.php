<?php

namespace Database\Seeders;

use App\Models\MasterIncomeType;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterIncomeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $incomeTypes = ['Salary', 'Pinjaman', 'Revenue'];
        foreach ($users as $user) {
            foreach ($incomeTypes as $t) {
                MasterIncomeType::create(
                    [
                        'user_id' => $user->id, 
                        'slug'    => str($t)->slug(),
                        'name'    => $t
                    ]
                );
            }
        }
    }
}
