<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
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
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'type' => $this->whenLoaded('type', 
                function() {
                    return [
                        'id' => $this->type->id,
                        'name' => $this->type->name,
                        'slug' => $this->type->slug,
                    ];
                }
            ),
            'payment' => $this->whenLoaded('payment', 
                function() {
                    return [
                        'id' => $this->payment->id,
                        'name' => $this->payment->name,
                    ];
                }
            ),
            'note' => $this->note,
            'date' => $this->date,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
