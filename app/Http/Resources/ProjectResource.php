<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'created_by'  => $this->created_by,
            'role'        => $this->whenPivotLoaded('project_user', fn () => $this->pivot->role),
            'creator'     => new UserResource($this->whenLoaded('creator')),
            'members'     => ProjectMemberResource::collection($this->whenLoaded('users')),
            'created_at'  => $this->created_at?->format('d-m-Y H:i'),
            'updated_at'  => $this->updated_at?->format('d-m-Y H:i'),
        ];
    }
}
