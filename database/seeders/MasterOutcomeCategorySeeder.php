<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterOutcomeCategory;

class MasterOutcomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $outcomeCategories = ['Rumah', 'Type', 'Biaya Hidup', 'Piutang', 'Renovasi Rumah', 'Kendaraan', 'Rokok', 'Donasi', 'Kebutuhan Anak'];
        foreach ($users as $user) {
            foreach ($outcomeCategories as $c) {
                MasterOutcomeCategory::create(['user_id' => $user->id, 'name' => $c, 'slug' => str($c)->slug()]);
            }
        }
    }
}
