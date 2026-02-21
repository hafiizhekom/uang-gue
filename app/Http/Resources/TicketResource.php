<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;    
use App\Http\Resources\TicketCategoryResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TicketAttachmentResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => new TicketCategoryResource($this->whenLoaded('category')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'status' => $this->status,
            'priority' => $this->priority,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'attachments' => TicketAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
