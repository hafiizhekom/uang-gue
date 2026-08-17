<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportRangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'range' => $this['range'],

            'summary' => $this['summary'],

            // Table + comparison per period (bulan ke bulan)
            'periods' => $this['periods'],

            'chart_data' => [
                'outcome_by_category_per_period' => $this['outcome_by_category_per_period'],
                'outcome_by_type_per_period'      => $this['outcome_by_type_per_period'],
                'outcome_by_tags_per_period'     => $this['outcome_by_tags_per_period'],
                'income_by_type_per_period'      => $this['income_by_type_per_period'],
            ],
        ];
    }
}