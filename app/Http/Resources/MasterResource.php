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
            'slug' => $this->slug,
            'count_incomes' => $this->whenCounted('incomes'),
            'count_outcomes' => $this->whenCounted('outcomes'),
            'count_outcome_details' => $this->whenCounted('outcome_details'),
        ];
    }
}
