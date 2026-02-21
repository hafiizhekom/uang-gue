<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterOutcomeHutang;

class MasterOutcomeHutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $hutangs = ['Pinjaman', 'PayLater', 'Cicilan'];
        foreach ($users as $user) {
            foreach ($hutangs as $h) {
                MasterOutcomeHutang::create(['user_id' => $user->id, 'name' => $h, 'slug' => str($h)->slug()]);
            }
        }
    }
}
