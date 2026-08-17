<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'period' => $this['period'],

            'balance' => $this['balance'],

            // Table Pengeluaran / hari
            'daily_table' => $this['daily_table'],

            'daily_chart_data' => [
                'expense_breakdown' => [
                    'by_category' => $this['daily_by_category'],
                    'by_type'     => $this['daily_by_type'],
                    'by_tags'     => $this['daily_by_tags'],
                ],

                'income_breakdown' => [
                    'by_type' => $this['daily_income_by_type'],
                ],
            ],
        ];
    }
}