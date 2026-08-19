<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRangeRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportRangeResource;
use App\Http\Resources\ReportResource;
use App\Models\Income;
use App\Models\MasterPeriod;
use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Single-period report: daily table + daily breakdown charts.
     * GET /report/{period_id}
     */
    public function index(ReportRequest $request)
    {
        $userId = auth()->id();
        $period = MasterPeriod::find($request->master_period_id);

        $balance = $this->period_balance($period, $userId);

        $data = [
            'period' => [
                'id'         => $period->id,
                'name'       => $period->name,
                'start_date' => $period->start_date,
                'end_date'   => $period->end_date,
                'opening_balance' => (float) $period->opening_balance,
            ],

            'balance' => $balance,

            // Table Pengeluaran / hari
            'daily_table' => $this->daily_table($period),

            // Chart: pengeluaran / category / hari
            'daily_by_category' => $this->daily_outcome_pivot($period, 'master_outcome_categories', 'master_outcome_category_id'),

            // Chart: pengeluaran / type / hari
            'daily_by_type' => $this->daily_outcome_pivot($period, 'master_outcome_types', 'master_outcome_type_id'),

            // Chart: pengeluaran / tags / hari
            'daily_by_tags' => $this->daily_tags_pivot($period, $userId),

            // Chart: pemasukan / type / hari
            'daily_income_by_type' => $this->daily_income_pivot($period),
        ];

        return new ReportResource($data);
    }

    /**
     * Multi-period range report: compares periods within a date range
     * (e.g. a full year) — which period was most wasteful/frugal, and
     * whether spending is trending up or down over time.
     * GET /report-range?start_date=...&end_date=...
     */
    public function range(ReportRangeRequest $request)
    {
        $userId = auth()->id();

        $periods = MasterPeriod::where('user_id', $userId)
            ->where('start_date', '>=', $request->start_date)
            ->where('end_date', '<=', $request->end_date)
            ->orderBy('start_date')
            ->get();

        if ($periods->isEmpty()) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Tidak ada periode dalam rentang tanggal tersebut.',
            ], 404);
        }

        $periodStats = $periods->map(function ($period) use ($userId) {
            return array_merge([
                'id'         => $period->id,
                'name'       => $period->name,
                'start_date' => $period->start_date,
                'end_date'   => $period->end_date,
            ], $this->period_balance($period, $userId));
        });

        $data = [
            'range' => [
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
            ],

            'summary' => $this->range_summary($periodStats),

            'periods' => $this->range_comparison($periodStats),

            'outcome_by_category_per_period' => $this->outcome_per_period($periods, 'master_outcome_categories', 'master_outcome_category_id'),
            'outcome_by_type_per_period'     => $this->outcome_per_period($periods, 'master_outcome_types', 'master_outcome_type_id'),
            'outcome_by_tags_per_period'     => $this->tags_per_period($periods, $userId),
            'income_by_type_per_period'      => $this->income_by_type_per_period($periods),
        ];

        return new ReportRangeResource($data);
    }

    /**
     * Pivot: income amount per period, broken down by income type.
     */
    private function income_by_type_per_period($periods)
    {
        $periodIds = $periods->pluck('id');

        $rows = Income::whereIn('incomes.master_period_id', $periodIds)
            ->join('master_income_types', 'incomes.master_income_type_id', '=', 'master_income_types.id')
            ->whereNull('master_income_types.deleted_at')
            ->select(
                'incomes.master_period_id',
                'master_income_types.name as name',
                DB::raw('SUM(incomes.amount) as total')
            )
            ->groupBy('incomes.master_period_id', 'master_income_types.name')
            ->get();

        $series = $rows->pluck('name')->unique()->values()->all();

        $matrix = [];
        foreach ($periods as $period) {
            $entry = ['period' => $period->name, 'period_id' => $period->id];
            foreach ($series as $name) {
                $entry[$name] = 0;
            }
            $matrix[$period->id] = $entry;
        }

        foreach ($rows as $row) {
            if (isset($matrix[$row->master_period_id])) {
                $matrix[$row->master_period_id][$row->name] = (float) $row->total;
            }
        }

        return [
            'series' => $series,
            'data'   => array_values($matrix),
        ];
    }

    /**
     * Pivot: outcome amount per period, broken down by tag.
     * Tags live at the outcome_details level.
     */
    private function tags_per_period($periods, $userId)
    {
        $periodIds = $periods->pluck('id');

        $rows = DB::table('master_outcome_detail_tags')
            ->join('outcome_detail_tag', 'master_outcome_detail_tags.id', '=', 'outcome_detail_tag.master_outcome_detail_tag_id')
            ->join('outcome_details', 'outcome_detail_tag.outcome_detail_id', '=', 'outcome_details.id')
            ->join('outcomes', 'outcome_details.outcome_id', '=', 'outcomes.id')
            ->join('master_outcome_categories', 'outcomes.master_outcome_category_id', '=', 'master_outcome_categories.id')
            ->whereIn('outcomes.master_period_id', $periodIds)
            ->where('master_outcome_categories.is_counted', true)
            ->where('master_outcome_detail_tags.user_id', $userId)
            ->whereNull('master_outcome_detail_tags.deleted_at')
            ->select(
                'outcomes.master_period_id',
                'master_outcome_detail_tags.name as name',
                DB::raw('SUM(outcome_details.amount) as total')
            )
            ->groupBy('outcomes.master_period_id', 'master_outcome_detail_tags.name')
            ->get();

        $series = $rows->pluck('name')->unique()->values()->all();

        $matrix = [];
        foreach ($periods as $period) {
            $entry = ['period' => $period->name, 'period_id' => $period->id];
            foreach ($series as $name) {
                $entry[$name] = 0;
            }
            $matrix[$period->id] = $entry;
        }

        foreach ($rows as $row) {
            if (isset($matrix[$row->master_period_id])) {
                $matrix[$row->master_period_id][$row->name] = (float) $row->total;
            }
        }

        return [
            'series' => $series,
            'data'   => array_values($matrix),
        ];
    }

    /**
     * Per-period balance snapshot.
     *
     * - opening_balance: saldo awal period (input manual atau carry-over dari period sebelumnya)
     * - closing_balance: saldo akhir period = opening_balance + income - outcome (posisi riil setelah period ini)
     * - period_surplus_deficit: performa period itu sendiri (income - outcome), TANPA mempertimbangkan opening_balance.
     *   Ini yang paling penting buat comparison table: sebuah period bisa closing_balance-nya masih positif
     *   (karena numpang opening_balance gede dari bulan lalu) padahal sebenarnya period itu defisit
     *   (outcome > income). net_savings/closing_balance doang gak bakal keliatan hal ini.
     */
    private function period_balance($period, $userId)
    {
        $totalIncome  = (float) Income::where('master_period_id', $period->id)->sum('amount');
        $totalOutcome = (float) Outcome::counted()->where('master_period_id', $period->id)->sum('amount');
        $openingBalance = (float) $period->opening_balance;

        $periodSurplusDeficit = $totalIncome - $totalOutcome;
        $closingBalance = $openingBalance + $periodSurplusDeficit;

        $countTransactions = Income::where('master_period_id', $period->id)->count()
            + Outcome::counted()->where('master_period_id', $period->id)->count();

        return [
            'total_income'           => $totalIncome,
            'total_outcome'          => $totalOutcome,
            'opening_balance'        => $openingBalance,
            'closing_balance'        => $closingBalance,
            'period_surplus_deficit' => $periodSurplusDeficit,
            'is_deficit_period'      => $periodSurplusDeficit < 0,
            // net_savings dipertahankan sebagai alias closing_balance biar konsumen lama (single-period report) gak break.
            'net_savings'            => $closingBalance,
            'count_transactions'     => $countTransactions,
        ];
    }

    /**
     * Table: total income & outcome per day across the period range.
     */
    private function daily_table($period)
    {
        $dailyIncome = Income::where('master_period_id', $period->id)
            ->select(DB::raw('DATE(date) as transaction_date'), DB::raw('SUM(amount) as total'))
            ->groupBy('transaction_date')
            ->pluck('total', 'transaction_date');

        $outcomeFromDetails = DB::table('outcome_details')
            ->join('outcomes', 'outcome_details.outcome_id', '=', 'outcomes.id')
            ->join('master_outcome_categories', 'outcomes.master_outcome_category_id', '=', 'master_outcome_categories.id')
            ->where('outcomes.master_period_id', $period->id)
            ->whereNull('outcome_details.deleted_at')
            ->where('master_outcome_categories.is_counted', true)
            ->select(DB::raw('DATE(outcome_details.date) as transaction_date'), DB::raw('SUM(outcome_details.amount) as total'))
            ->groupBy('transaction_date')
            ->pluck('total', 'transaction_date');

        $outcomeFromParentOnly = Outcome::where('master_period_id', $period->id)
            ->counted()
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('outcome_details')
                    ->whereRaw('outcome_details.outcome_id = outcomes.id');
            })
            ->select(DB::raw('DATE(date) as transaction_date'), DB::raw('SUM(amount) as total'))
            ->groupBy('transaction_date')
            ->pluck('total', 'transaction_date');

        $trendData = [];
        foreach ($this->dateRange($period) as $dateStr) {
            $totalDailyOutcome = ($outcomeFromDetails[$dateStr] ?? 0) + ($outcomeFromParentOnly[$dateStr] ?? 0);

            $trendData[] = [
                'date'          => $dateStr,
                'income_total'  => (float) ($dailyIncome[$dateStr] ?? 0),
                'outcome_total' => (float) $totalDailyOutcome,
            ];
        }

        return $trendData;
    }

    /**
     * Pivot: outcome amount per day, broken down by a related master table (category or type).
     * Uses outcomes.amount / outcomes.date directly (parent-level), matching Dashboard's aggregate logic.
     */
    private function daily_outcome_pivot($period, string $relatedTable, string $foreignKey)
    {
        $rows = Outcome::where('outcomes.master_period_id', $period->id)
            ->join($relatedTable, "outcomes.{$foreignKey}", '=', "{$relatedTable}.id")
            ->when($relatedTable === 'master_outcome_categories', fn ($query) => $query
                ->where('master_outcome_categories.is_counted', true))
            ->when($relatedTable !== 'master_outcome_categories', fn ($query) => $query
                ->join('master_outcome_categories', 'outcomes.master_outcome_category_id', '=', 'master_outcome_categories.id')
                ->where('master_outcome_categories.is_counted', true))
            ->whereNull("{$relatedTable}.deleted_at")
            ->select(
                DB::raw('DATE(outcomes.date) as transaction_date'),
                "{$relatedTable}.name as name",
                DB::raw('SUM(outcomes.amount) as total')
            )
            ->groupBy('transaction_date', "{$relatedTable}.name")
            ->get();

        return $this->buildDailyPivot($rows, $period);
    }

    /**
     * Pivot: outcome amount per day, broken down by tag. Tags live at the outcome_details level.
     */
    private function daily_tags_pivot($period, $userId)
    {
        $rows = DB::table('master_outcome_detail_tags')
            ->join('outcome_detail_tag', 'master_outcome_detail_tags.id', '=', 'outcome_detail_tag.master_outcome_detail_tag_id')
            ->join('outcome_details', 'outcome_detail_tag.outcome_detail_id', '=', 'outcome_details.id')
            ->join('outcomes', 'outcome_details.outcome_id', '=', 'outcomes.id')
            ->where('outcomes.master_period_id', $period->id)
            ->where('master_outcome_detail_tags.user_id', $userId)
            ->whereNull('master_outcome_detail_tags.deleted_at')
            ->select(
                DB::raw('DATE(outcome_details.date) as transaction_date'),
                'master_outcome_detail_tags.name as name',
                DB::raw('SUM(outcome_details.amount) as total')
            )
            ->groupBy('transaction_date', 'master_outcome_detail_tags.name')
            ->get();

        return $this->buildDailyPivot($rows, $period);
    }

    /**
     * Pivot: income amount per day, broken down by income type.
     */
    private function daily_income_pivot($period)
    {
        $rows = Income::where('incomes.master_period_id', $period->id)
            ->join('master_income_types', 'incomes.master_income_type_id', '=', 'master_income_types.id')
            ->whereNull('master_income_types.deleted_at')
            ->select(
                DB::raw('DATE(incomes.date) as transaction_date'),
                'master_income_types.name as name',
                DB::raw('SUM(incomes.amount) as total')
            )
            ->groupBy('transaction_date', 'master_income_types.name')
            ->get();

        return $this->buildDailyPivot($rows, $period);
    }

    /**
     * Turns a flat (transaction_date, name, total) collection into a pivoted shape
     * ready for a stacked/grouped chart:
     *   { series: ['Food', 'Transport', ...], data: [{ date, Food: 12000, Transport: 5000 }, ...] }
     */
    private function buildDailyPivot($rows, $period)
    {
        $series = $rows->pluck('name')->unique()->values()->all();

        $matrix = [];
        foreach ($this->dateRange($period) as $dateStr) {
            $entry = ['date' => $dateStr];
            foreach ($series as $name) {
                $entry[$name] = 0;
            }
            $matrix[$dateStr] = $entry;
        }

        foreach ($rows as $row) {
            $dateStr = $row->transaction_date;
            if (isset($matrix[$dateStr])) {
                $matrix[$dateStr][$row->name] = (float) $row->total;
            }
        }

        return [
            'series' => $series,
            'data'   => array_values($matrix),
        ];
    }

    /**
     * Yields Y-m-d date strings from period start_date to end_date inclusive.
     */
    private function dateRange($period): array
    {
        $dates = [];
        $current = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);

        while ($current->lte($end)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        return $dates;
    }

    /**
     * Attaches period-over-period comparison to each period:
     * - outcome trend (existing): apakah pengeluaran naik/turun dibanding period sebelumnya
     * - opening_balance_gap: selisih opening_balance period ini dengan closing_balance period sebelumnya.
     *   Idealnya 0 (saldo nyambung mulus antar period). Kalau tidak 0, artinya user input manual
     *   opening_balance yang berbeda dari carry-over otomatis (misal ada dana masuk/keluar di luar
     *   pencatatan income/outcome — cash injection, penarikan tabungan lain, dsb).
     */
    private function range_comparison($periodStats)
    {
        $result = [];
        $prev = null;

        foreach ($periodStats as $stat) {
            $changeAmount  = $prev ? $stat['total_outcome'] - $prev['total_outcome'] : null;
            $changePercent = ($prev && $prev['total_outcome'] > 0)
                ? round(($changeAmount / $prev['total_outcome']) * 100, 2)
                : null;

            $trend = is_null($changeAmount)
                ? null
                : ($changeAmount > 0 ? 'up' : ($changeAmount < 0 ? 'down' : 'flat'));
            // 'up' = outcome-nya lebih besar dari period sebelumnya (makin boros)
            // 'down' = outcome-nya lebih kecil dari period sebelumnya (makin hemat)

            $openingBalanceGap = $prev ? round($stat['opening_balance'] - $prev['closing_balance'], 2) : null;

            $result[] = array_merge($stat, [
                'outcome_change_amount'  => $changeAmount,
                'outcome_change_percent' => $changePercent,
                'trend'                  => $trend,
                'opening_balance_gap'    => $openingBalanceGap,
                'opening_balance_continuous' => is_null($openingBalanceGap) ? null : (abs($openingBalanceGap) < 0.01),
            ]);

            $prev = $stat;
        }

        return $result;
    }

    /**
     * Range-level summary: totals, which period was most wasteful/frugal,
     * overall spending trend, and how the actual cash balance grew/shrank
     * across the whole range (starting opening_balance -> ending closing_balance).
     */
    private function range_summary($periodStats)
    {
        $totalIncome  = $periodStats->sum('total_income');
        $totalOutcome = $periodStats->sum('total_outcome');

        $firstPeriod = $periodStats->first();
        $lastPeriod  = $periodStats->last();

        $startingBalance = $firstPeriod ? $firstPeriod['opening_balance'] : 0;
        $endingBalance    = $lastPeriod ? $lastPeriod['closing_balance'] : 0;
        $totalBalanceGrowth = $endingBalance - $startingBalance;

        $mostWasteful = $periodStats->sortByDesc('total_outcome')->first();
        $mostFrugal   = $periodStats->sortBy('total_outcome')->first();

        // Period dengan performa terburuk/terbaik berdasarkan surplus/defisit MURNI (income - outcome),
        // beda dengan most_wasteful/most_frugal yang cuma lihat besaran outcome mentah.
        $mostDeficit = $periodStats->sortBy('period_surplus_deficit')->first();
        $mostSurplus = $periodStats->sortByDesc('period_surplus_deficit')->first();

        $deficitPeriodsCount = $periodStats->where('is_deficit_period', true)->count();

        // Rata-rata persentase perubahan outcome antar period berurutan.
        $changes = [];
        $prev = null;
        foreach ($periodStats as $stat) {
            if ($prev && $prev['total_outcome'] > 0) {
                $changes[] = (($stat['total_outcome'] - $prev['total_outcome']) / $prev['total_outcome']) * 100;
            }
            $prev = $stat;
        }
        $avgChangePercent = count($changes) > 0 ? array_sum($changes) / count($changes) : null;

        $overallTrend = is_null($avgChangePercent)
            ? 'not_enough_data'
            : ($avgChangePercent > 5 ? 'increasingly_wasteful' : ($avgChangePercent < -5 ? 'increasingly_frugal' : 'stable'));

        return [
            'total_periods'          => $periodStats->count(),
            'starting_balance'       => $startingBalance,
            'ending_balance'         => $endingBalance,
            'total_balance_growth'   => $totalBalanceGrowth,
            'total_income'           => $totalIncome,
            'total_outcome'          => $totalOutcome,
            'net_savings'            => $totalBalanceGrowth, // alias, konsisten sama makna closing - opening
            'avg_outcome_per_period' => $periodStats->count() > 0 ? round($totalOutcome / $periodStats->count(), 2) : 0,
            'deficit_periods_count'  => $deficitPeriodsCount,
            'most_wasteful_period'   => $mostWasteful ? [
                'id' => $mostWasteful['id'], 'name' => $mostWasteful['name'], 'total_outcome' => $mostWasteful['total_outcome'],
            ] : null,
            'most_frugal_period' => $mostFrugal ? [
                'id' => $mostFrugal['id'], 'name' => $mostFrugal['name'], 'total_outcome' => $mostFrugal['total_outcome'],
            ] : null,
            'most_deficit_period' => $mostDeficit ? [
                'id' => $mostDeficit['id'], 'name' => $mostDeficit['name'], 'period_surplus_deficit' => $mostDeficit['period_surplus_deficit'],
            ] : null,
            'most_surplus_period' => $mostSurplus ? [
                'id' => $mostSurplus['id'], 'name' => $mostSurplus['name'], 'period_surplus_deficit' => $mostSurplus['period_surplus_deficit'],
            ] : null,
            'avg_change_percent' => is_null($avgChangePercent) ? null : round($avgChangePercent, 2),
            'overall_trend'       => $overallTrend,
        ];
    }

    /**
     * Pivot: outcome amount per period, broken down by a related master table
     * (category or type) — buat stacked/grouped chart perbandingan antar period.
     *   { series: ['Food', 'Transport', ...], data: [{ period, period_id, Food: x, Transport: y }] }
     */
    private function outcome_per_period($periods, string $relatedTable, string $foreignKey)
    {
        $periodIds = $periods->pluck('id');

        $rows = Outcome::whereIn('outcomes.master_period_id', $periodIds)
            ->join($relatedTable, "outcomes.{$foreignKey}", '=', "{$relatedTable}.id")
            ->when($relatedTable === 'master_outcome_categories', fn ($query) => $query
                ->where('master_outcome_categories.is_counted', true))
            ->when($relatedTable !== 'master_outcome_categories', fn ($query) => $query
                ->join('master_outcome_categories', 'outcomes.master_outcome_category_id', '=', 'master_outcome_categories.id')
                ->where('master_outcome_categories.is_counted', true))
            ->whereNull("{$relatedTable}.deleted_at")
            ->select(
                'outcomes.master_period_id',
                "{$relatedTable}.name as name",
                DB::raw('SUM(outcomes.amount) as total')
            )
            ->groupBy('outcomes.master_period_id', "{$relatedTable}.name")
            ->get();

        $series = $rows->pluck('name')->unique()->values()->all();

        $matrix = [];
        foreach ($periods as $period) {
            $entry = ['period' => $period->name, 'period_id' => $period->id];
            foreach ($series as $name) {
                $entry[$name] = 0;
            }
            $matrix[$period->id] = $entry;
        }

        foreach ($rows as $row) {
            if (isset($matrix[$row->master_period_id])) {
                $matrix[$row->master_period_id][$row->name] = (float) $row->total;
            }
        }

        return [
            'series' => $series,
            'data'   => array_values($matrix),
        ];
    }
}