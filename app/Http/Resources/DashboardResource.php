<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'last_period_balance' => [
                'total_wallet_amount' => $this['total_wallet_amount'],
                'monthly_income' => $this['monthly_income'],
                'monthly_outcome' => $this['monthly_outcome'],
                'net_savings' => $this['net_savings'],
                'status' => $this['status'],
                'active_period' => $this['active_period'],
                'period_range' => $this['period_range'],
            ],

            'active_wallets' => $this['active_wallets'],

            'last_period_chart_data' => [
                'expense_breakdown' => [
                    'by_category' => $this['by_category'],
                    'by_type'     => $this['by_type'],
                    'by_tags'     => $this['by_tags'],
                ],

                'income_breakdown' => [
                    'by_type' => $this['income_by_type'],
                ],
            ],

            'last_period_trend' => $this['last_period_trend'], 
        ];
    }
}
