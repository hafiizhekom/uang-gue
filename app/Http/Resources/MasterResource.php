<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug'       => $this->slug,
            
            'balance'    => $this->when(isset($this->balance), (float) $this->balance),
            'start_date' => $this->when(isset($this->start_date), $this->start_date),
            'end_date'   => $this->when(isset($this->end_date), $this->end_date),

            'count_incomes' => $this->whenCounted('incomes'),
            'count_outcomes' => $this->whenCounted('outcomes'),
            'count_outcome_details' => $this->whenCounted('outcome_details'),
        ];
    }
}
