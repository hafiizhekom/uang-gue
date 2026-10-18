<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'project_id'   => $this->project_id,
            'log_name'     => $this->log_name,
            'description'  => $this->description,
            'subject_type' => class_basename($this->subject_type),
            'subject_id'   => $this->subject_id,
            'causer'       => new UserResource($this->whenLoaded('causer')),
            'properties'   => $this->properties,
            'created_at'   => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
