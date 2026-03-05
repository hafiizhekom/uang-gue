<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterOutcomeType;

class MasterOutcomeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $types = ['Pinjaman', 'PayLater', 'Cicilan'];
        foreach ($users as $user) {
            foreach ($types as $h) {
                MasterOutcomeType::create(['user_id' => $user->id, 'name' => $h, 'slug' => str($h)->slug()]);
            }
        }
    }
}
