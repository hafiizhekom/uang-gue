<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'email'    => $this->email,
            'initials' => collect(explode(' ', $this->name))
                            ->map(fn($n) => mb_strtoupper(mb_substr($n, 0, 1)))
                            ->join(''),
            'role'     => $this->whenPivotLoaded('project_user', fn () => $this->pivot->role),
        ];
    }
}
