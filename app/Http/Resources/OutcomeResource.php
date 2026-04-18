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
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'has_detail' => (bool) $this->has_detail,
            'details' => OutcomeDetailResource::collection($this->whenLoaded('details')),
            'category' => $this->whenLoaded('category', 
                function() {
                    return [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'slug' => $this->category->slug,
                    ];
                }
            ),
            'payment' => $this->whenLoaded('payment', 
                function() {
                    return [
                        'id' => $this->payment->id,
                        'name' => $this->payment->name,
                        'slug' => $this->payment->slug,
                    ];
                }
            ),
            'type' => $this->whenLoaded('type', 
                function() {
                    return [
                        'id' => $this->type->id,
                        'name' => $this->type->name,
                        'slug' => $this->type->slug,
                    ];
                }
            ),
            'note' => $this->note,
            'date' => $this->date->format('d-m-Y'),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
