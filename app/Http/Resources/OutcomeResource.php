<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutcomeResource extends JsonResource
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
            'date' => $this->date,
            'title' => $this->title,
            'total_amount' => (float) $this->amount,
            'has_detail' => (bool) $this->has_detail,
            'category' => $this->category?->name,
            'payment' => $this->payment?->name, // Payment di level parent (kalau has_detail false)
            'details' => OutcomeDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
