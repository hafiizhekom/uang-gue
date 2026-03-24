<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardRequest;
use App\Models\Income;
use App\Models\MasterPayment;
use Illuminate\Support\Facades\Cache;
use App\Models\Outcome;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    public function index(DashboardRequest $request)
    {
        $userId = auth()->id();
        $period = $request->active_period;

        $cacheKey = "dashboard_user_{$userId}_period_{$period}";
        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($period, $userId) {
            $last_period_balance = $period ? $this->last_period_balance($period, $userId) : [
                'monthlyIncome' => 0,
                'monthlyOutcome' => 0,
                'netSavings' => 0,
                'status' => 'balance',
            ];

            $last_period_chart = $period ? $this->last_period_chart($period, $userId) : [
                'byCategory' => [],
                'byType' => [],
                'byTags' => [],
                'incomeByType' => [],
            ];

            return [
                // Section Last Period Balance
                'total_wallet_amount' => (float) ($last_period_balance['totalWalletAmount'] ?? 0),
                'monthly_income'      => $last_period_balance['monthlyIncome'],
                'monthly_outcome'     => $last_period_balance['monthlyOutcome'],
                'net_savings'         => $last_period_balance['netSavings'],
                'status'              => $last_period_balance['status'],
                'active_period'       => $period ? $period->name : 'No Active Period',
                'period_range'        => $period ? "{$period->start_date} to {$period->end_date}" : null,

                // Section Active Wallets
                'active_wallets'      => $this->active_wallets($userId),

                // Section Last Period Chart Data
                'by_category'         => $last_period_chart['byCategory'],
                'by_type'             => $last_period_chart['byType'],
                'by_tags'             => $last_period_chart['byTags'],
                'income_by_type'      => $last_period_chart['incomeByType'],

                // Section Daily Trend (Line Chart)
                'last_period_trend'   => $period ? $this->last_period_trend($period) : [],
            ];
            
        });
        
        return new DashboardResource($data);
    }

    private function active_wallets($userId)
    {
        $activeWallets = MasterPayment::where('user_id', $userId)
        ->whereNull('deleted_at')
        ->orderByDesc('updated_at')
        ->limit(4)
        ->get()
        ->map(function ($wallet) {
            return [
                'name'   => $wallet->name,
                'amount' => (float) $wallet->balance,
            ];
        });

        return $activeWallets;
    }

    private function last_period_balance($period, $userId)
    {
        $totalWalletAmount = MasterPayment::where('user_id', $userId)->sum('balance');
        $monthlyIncome = $period ? (float) Income::where('master_period_id', $period->id)->sum('amount') : 0;
        $monthlyOutcome = $period ? (float) Outcome::where('master_period_id', $period->id)->sum('amount') : 0;

        $netSavings = $monthlyIncome - $monthlyOutcome;

        $status = 'balance';
        if ($totalWalletAmount > $netSavings) {
            $status = 'over'; // Saldo di wallet lebih gede dari catatan nabung bulan ini
        } elseif ($totalWalletAmount < $netSavings) {
            $status = 'under'; // Saldo di wallet lebih kecil dari catatan nabung
        }

        return compact('totalWalletAmount', 'monthlyIncome', 'monthlyOutcome', 'netSavings', 'status');
    }

    private function last_period_chart($period, $userId)
    {
        $formatData = fn($collection) => $collection->map(fn($item) => [
            'name' => $item->name,
            'total' => (float) $item->total, // Paksa jadi float di sini
        ]);
        
        // Section Last Period Chart Data
        $byCategory = Outcome::where('master_period_id', $period->id)
        ->join('master_outcome_categories', 'outcomes.master_outcome_category_id', '=', 'master_outcome_categories.id')
        ->select('master_outcome_categories.name', \DB::raw('SUM(outcomes.amount) as total'))
        ->groupBy('master_outcome_categories.name')
        ->get();

        $byType = Outcome::where('master_period_id', $period->id)
            ->join('master_outcome_types', 'outcomes.master_outcome_type_id', '=', 'master_outcome_types.id')
            ->select('master_outcome_types.name', \DB::raw('SUM(outcomes.amount) as total'))
            ->groupBy('master_outcome_types.name')
            ->get();

        $byTags = \DB::table('master_outcome_detail_tags')
        ->join('outcome_detail_tag', 'master_outcome_detail_tags.id', '=', 'outcome_detail_tag.master_outcome_detail_tag_id')
        ->join('outcome_details', 'outcome_detail_tag.outcome_detail_id', '=', 'outcome_details.id')
        ->join('outcomes', 'outcome_details.outcome_id', '=', 'outcomes.id')
        ->where('outcomes.master_period_id', $period->id)
        ->where('master_outcome_detail_tags.user_id', $userId)
        ->select('master_outcome_detail_tags.name', \DB::raw('SUM(outcome_details.amount) as total'))
        ->groupBy('master_outcome_detail_tags.name')
        ->get();

        $incomeByType = Income::where('master_period_id', $period->id)
        ->join('master_income_types', 'incomes.master_income_type_id', '=', 'master_income_types.id')
        ->select('master_income_types.name', \DB::raw('SUM(incomes.amount) as total'))
        ->groupBy('master_income_types.name')
        ->get();

        return [
            'byCategory'   => $formatData($byCategory),
            'byType'       => $formatData($byType),
            'byTags'       => $formatData($byTags),
            'incomeByType' => $formatData($incomeByType),
        ];
    }

    private function last_period_trend($period)
    {
        $dailyIncome = Income::where('master_period_id', $period->id)
        ->select(\DB::raw('DATE(date) as transaction_date'), \DB::raw('SUM(amount) as total'))
        ->groupBy('transaction_date')
        ->orderBy('transaction_date')
        ->pluck('total', 'transaction_date'); // Pluck biar gampang dicari (key = date, value = total)

        // 2. Ambil data Outcome Harian (SUM amount by date)
        // Ingat, kita SUM outcome utamanya (yang punya total), bukan detailnya
        $dailyOutcome = Outcome::where('master_period_id', $period->id)
            ->select(\DB::raw('DATE(date) as transaction_date'), \DB::raw('SUM(amount) as total'))
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->pluck('total', 'transaction_date');

        $startDate = \Carbon\Carbon::parse($period->start_date);
        $endDate = \Carbon\Carbon::parse($period->end_date);

        $trendData = [];
        while ($startDate->lte($endDate)) {
            $dateStr = $startDate->toDateString();
            
            $trendData[] = [
                'date'          => $startDate->format('Y-m-d'),
                'income_total'  => (float) ($dailyIncome[$dateStr] ?? 0),
                'outcome_total' => (float) ($dailyOutcome[$dateStr] ?? 0),
            ];
            
            $startDate->addDay(); // Lanjut ke hari berikutnya
        }

        return $trendData;
    }
}
