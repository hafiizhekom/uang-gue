<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutcomeDetailResource extends JsonResource
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
            'payment' => $this->whenLoaded('payment', 
                function() {
                    return [
                        'id' => $this->payment->id,
                        'name' => $this->payment->name,
                        'slug' => $this->payment->slug,
                    ];
                }
            ),
            'tags' => $this->whenLoaded('tags', 
                function() {
                    return $this->tags->map(function($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                        ];
                    });
                }
            ),
            'note' => $this->note,
            'date' => $this->date->format('d-m-Y'),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
