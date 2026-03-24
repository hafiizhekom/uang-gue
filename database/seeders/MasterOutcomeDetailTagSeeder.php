<?php

namespace Database\Seeders;

use App\Models\MasterOutcomeDetailTag;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterOutcomeDetailTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $tags = ['Bumbu Dapur', 'Kebutuhan Pokok', 'Air', 'Makan Diluar'];
        foreach ($users as $user) {
            foreach ($tags as $t) {
                MasterOutcomeDetailTag::updateOrCreate(['user_id' => $user->id, 'slug' => str($t)->slug()], ['name' => $t]);
            }
        }
    }
}
