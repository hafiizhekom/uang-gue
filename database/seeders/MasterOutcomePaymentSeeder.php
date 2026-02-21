<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterOutcomePayment;

class MasterOutcomePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $payments = ['Mandiri', 'BCA', 'BluBCA', 'Cash', 'OVO', 'Gopay', 'ShopeePay', 'Tokopedia Saldo', 'Dana'];
        foreach ($users as $user) {
            foreach ($payments as $p) {
                MasterOutcomePayment::create(['user_id' => $user->id, 'name' => $p, 'slug' => str($p)->slug()]);
            }
        }
    }
}
