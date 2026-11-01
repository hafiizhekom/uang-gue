<?php

namespace App\Services;

use App\Enums\ProjectRole;
use App\Models\User;
use App\Models\Project;
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
     * Inisialisasi default project, seluruh data master, dan template transaksi untuk user baru.
     */
    public function initializeNewUser(User $user): Project
    {
        return DB::transaction(function () use ($user) {
            // 1. Buat default project
            $project = Project::create([
                'name'        => $user->name . "'s Workspace",
                'description' => 'Default personal workspace for ' . $user->name,
                'created_by'  => $user->id,
            ]);

            // 2. Attach user sebagai owner
            $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

            // 3. Inisialisasi data master & template transaksi di project ini
            $this->initializeProjectData($project, $user);

            return $project;
        });
    }

    /**
     * Inisialisasi master data dan template transaksi untuk suatu project.
     */
    public function initializeProjectData(Project $project, ?User $creator = null): void
    {
        $userId = $creator ? $creator->id : $project->created_by;

        // --- 1. MASTER DATA SETUP ---

        // Create Master Period (Bulan Berjalan)
        $now = Carbon::now();
        $period = MasterPeriod::create([
            'project_id' => $project->id,
            'user_id'    => $userId,
            'name'       => $now->format('F Y'),
            'start_date' => $now->startOfMonth()->toDateString(),
            'end_date'   => $now->endOfMonth()->toDateString(),
        ]);

        // Create Master Payments
        $payments = ['Mandiri', 'BCA', 'BluBCA', 'Cash', 'OVO', 'Gopay', 'ShopeePay', 'Tokopedia Saldo', 'Dana'];
        foreach ($payments as $p) {
            MasterPayment::create([
                'project_id' => $project->id,
                'user_id'    => $userId, 
                'name'       => $p, 
                'slug'       => str($p)->slug(),
                'balance'    => 0
            ]);
        }

        // Create Master Outcome Categories
        $outcomeCategories = ['Rumah', 'Type', 'Biaya Hidup', 'Piutang', 'Renovasi Rumah', 'Kendaraan', 'Rokok', 'Donasi', 'Kebutuhan Anak', 'Hutang'];
        foreach ($outcomeCategories as $c) {
            MasterOutcomeCategory::create([
                'project_id' => $project->id,
                'user_id'    => $userId, 
                'name'       => $c, 
                'slug'       => str($c)->slug()
            ]);
        }

        // Create Master Outcome Types
        $outcomeTypes = ['PayLater', 'Cicilan'];
        foreach ($outcomeTypes as $ot) {
            MasterOutcomeType::create([
                'project_id' => $project->id,
                'user_id'    => $userId, 
                'name'       => $ot, 
                'slug'       => str($ot)->slug()
            ]);
        }

        // Create Master Income Types
        $incomeTypes = ['Gaji', 'Pinjaman', 'Hasil Freelance'];
        foreach ($incomeTypes as $it) {
            MasterIncomeType::create([
                'project_id' => $project->id,
                'user_id'    => $userId, 
                'name'       => $it, 
                'slug'       => str($it)->slug()
            ]);
        }

        // Create Master Outcome Detail Tags
        $tags = ['Bumbu Dapur', 'Kebutuhan Pokok', 'Air', 'Makan Diluar'];
        foreach ($tags as $t) {
            MasterOutcomeDetailTag::create([
                'project_id' => $project->id,
                'user_id'    => $userId,
                'name'       => $t, 
                'slug'       => str($t)->slug()
            ]);
        }

        // --- 2. TEMPLATE TRANSACTIONS SETUP ---
        $this->createInitialTemplates($project, $userId, $period);
    }

    /**
     * Membuat template transaksi (Income, Outcome Non-Detail, Outcome With Detail) untuk project.
     */
    private function createInitialTemplates(Project $project, int $userId, MasterPeriod $period): void
    {
        $paymentCash = MasterPayment::where('project_id', $project->id)->where('slug', 'cash')->first();
        $paymentBank = MasterPayment::where('project_id', $project->id)->where('slug', 'mandiri')->first();
        $outCategory = MasterOutcomeCategory::where('project_id', $project->id)->first();
        $outType     = MasterOutcomeType::where('project_id', $project->id)->first();
        $inType      = MasterIncomeType::where('project_id', $project->id)->where('slug', 'gaji')->first();
        $tagPokok    = MasterOutcomeDetailTag::where('project_id', $project->id)->where('slug', 'kebutuhan-pokok')->first();

        // 1. Template Income
        $project->incomes()->create([
            'project_id'            => $project->id,
            'user_id'               => $userId,
            'date'                  => now(),
            'title'                 => 'Gaji Bulanan',
            'amount'                => 5000000,
            'master_income_type_id' => $inType ? $inType->id : null,
            'master_payment_id'     => $paymentBank ? $paymentBank->id : null,
            'master_period_id'      => $period->id,
            'note'                  => 'Pendapatan rutin'
        ]);

        // 2. Template Outcome TANPA Detail
        $project->outcomes()->create([
            'project_id'                 => $project->id,
            'user_id'                    => $userId,
            'date'                       => now(),
            'title'                      => 'Beli Bensin',
            'amount'                     => 50000,
            'has_detail'                 => false,
            'master_outcome_category_id' => $outCategory ? $outCategory->id : null,
            'master_outcome_type_id'     => $outType ? $outType->id : null,
            'master_payment_id'          => $paymentCash ? $paymentCash->id : null,
            'master_period_id'           => $period->id,
            'note'                       => 'Pengeluaran langsung'
        ]);

        // 3. Template Outcome DENGAN Detail
        $outcomeWithDetail = $project->outcomes()->create([
            'project_id'                 => $project->id,
            'user_id'                    => $userId,
            'date'                       => now(),
            'title'                      => 'Belanja Mingguan',
            'amount'                     => 200000,
            'has_detail'                 => true,
            'master_outcome_category_id' => $outCategory ? $outCategory->id : null,
            'master_outcome_type_id'     => $outType ? $outType->id : null,
            'master_payment_id'          => $paymentCash ? $paymentCash->id : null,
            'master_period_id'           => $period->id,
            'note'                       => 'Pengeluaran dengan rincian item'
        ]);

        // Buat rincian (OutcomeDetail) untuk transaksi di atas
        $detail = $outcomeWithDetail->details()->create([
            'title'             => 'Beras, Minyak, & Telur',
            'amount'            => 200000,
            'date'              => now(),
            'master_payment_id' => $paymentCash ? $paymentCash->id : null,
        ]);

        if ($tagPokok) {
            $detail->tags()->attach($tagPokok->id);
        }
    }
}