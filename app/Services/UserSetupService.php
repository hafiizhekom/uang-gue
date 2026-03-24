<?php

namespace App\Services;

use App\Models\User;
use App\Models\MasterOutcomeCategory;
use App\Models\MasterOutcomeType;
use App\Models\MasterIncomeType;
use App\Models\MasterPayment;
use App\Models\MasterPeriod;
use App\Models\MasterOutcomeDetailTag;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserSetupService
{
    /**
     * Inisialisasi seluruh data master dan template transaksi untuk user baru.
     */
    public function initializeNewUser(User $user)
    {
        DB::transaction(function () use ($user) {
            // --- 1. MASTER DATA SETUP ---

            // Create Master Period (Bulan Berjalan)
            $now = Carbon::now();
            $period = MasterPeriod::create([
                'user_id'    => $user->id,
                'name'       => $now->format('F Y'),
                'start_date' => $now->startOfMonth()->toDateString(),
                'end_date'   => $now->endOfMonth()->toDateString(),
            ]);

            // Create Master Payments
            $payments = ['Mandiri', 'BCA', 'BluBCA', 'Cash', 'OVO', 'Gopay', 'ShopeePay', 'Tokopedia Saldo', 'Dana'];
            foreach ($payments as $p) {
                MasterPayment::create([
                    'user_id' => $user->id, 
                    'name'    => $p, 
                    'slug'    => str($p)->slug(),
                    'balance' => 0
                ]);
            }

            // Create Master Outcome Categories
            $outcomeCategories = ['Rumah', 'Type', 'Biaya Hidup', 'Piutang', 'Renovasi Rumah', 'Kendaraan', 'Rokok', 'Donasi', 'Kebutuhan Anak', 'Hutang'];
            foreach ($outcomeCategories as $c) {
                MasterOutcomeCategory::create([
                    'user_id' => $user->id, 
                    'name'    => $c, 
                    'slug'    => str($c)->slug()
                ]);
            }

            // Create Master Outcome Types
            $outcomeTypes = ['PayLater', 'Cicilan'];
            foreach ($outcomeTypes as $ot) {
                MasterOutcomeType::create([
                    'user_id' => $user->id, 
                    'name'    => $ot, 
                    'slug'    => str($ot)->slug()
                ]);
            }

            // Create Master Income Types
            $incomeTypes = ['Gaji', 'Pinjaman', 'Hasil Freelance'];
            foreach ($incomeTypes as $it) {
                MasterIncomeType::create([
                    'user_id' => $user->id, 
                    'name'    => $it,
                    'slug'    => str($it)->slug()
                ]);
            }

            // Create Master Outcome Detail Tags
            $tags = ['Bumbu Dapur', 'Kebutuhan Pokok', 'Air', 'Makan Diluar'];
            foreach ($tags as $t) {
                MasterOutcomeDetailTag::create([
                    'user_id' => $user->id,
                    'name'    => $t,
                    'slug'    => str($t)->slug()
                ]);
            }

            // --- 2. TEMPLATE TRANSACTIONS SETUP ---
            $this->createInitialTemplates($user, $period);
        });
    }

    /**
     * Membuat template transaksi (Income, Outcome Non-Detail, Outcome With Detail).
     */
    private function createInitialTemplates(User $user, $period)
    {
        // Ambil resource yang baru saja dibuat untuk relasi
        $paymentCash = MasterPayment::where('user_id', $user->id)->where('slug', 'cash')->first();
        $paymentBank = MasterPayment::where('user_id', $user->id)->where('slug', 'mandiri')->first();
        $outCategory = MasterOutcomeCategory::where('user_id', $user->id)->first();
        $outType     = MasterOutcomeType::where('user_id', $user->id)->first();
        $inType      = MasterIncomeType::where('user_id', $user->id)->where('slug', 'gaji')->first();
        $tagPokok    = MasterOutcomeDetailTag::where('user_id', $user->id)->where('slug', 'kebutuhan-pokok')->first();

        // 1. Template Income
        $user->incomes()->create([
            'user_id'               => $user->id,
            'date'                  => now(),
            'title'                 => 'Gaji Bulanan',
            'amount'                => 5000000,
            'master_income_type_id' => $inType->id,
            'master_payment_id'     => $paymentBank->id,
            'master_period_id'      => $period->id,
            'note'                  => 'Pendapatan rutin'
        ]);

        // 2. Template Outcome TANPA Detail (has_detail => false)
        $user->outcomes()->create([
            'user_id'                    => $user->id,
            'date'                       => now(),
            'title'                      => 'Beli Bensin',
            'amount'                     => 50000,
            'has_detail'                 => false,
            'master_outcome_category_id' => $outCategory->id,
            'master_outcome_type_id'     => $outType->id,
            'master_payment_id'          => $paymentCash->id,
            'master_period_id'           => $period->id,
            'note'                       => 'Pengeluaran langsung'
        ]);

        // 3. Template Outcome DENGAN Detail (has_detail => true)
        $outcomeWithDetail = $user->outcomes()->create([
            'user_id'                    => $user->id,
            'date'                       => now(),
            'title'                      => 'Belanja Mingguan',
            'amount'                     => 200000,
            'has_detail'                 => true,
            'master_outcome_category_id' => $outCategory->id,
            'master_outcome_type_id'     => $outType->id,
            'master_payment_id'          => $paymentCash->id,
            'master_period_id'           => $period->id,
            'note'                       => 'Pengeluaran dengan rincian item'
        ]);

        // Buat rincian (OutcomeDetail) untuk transaksi di atas
        $detail = $outcomeWithDetail->details()->create([
            'title'             => 'Beras, Minyak, & Telur',
            'amount'            => 200000,
            'date'              => now(),
            'master_payment_id' => $paymentCash->id,
        ]);

        // Attach Tag ke rincian tersebut
        if ($tagPokok) {
            $detail->tags()->attach($tagPokok->id);
        }
    }
}