<?php

namespace App\Policies;

use App\Models\MasterOutcomeType;
use App\Models\User;

class MasterOutcomeTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MasterOutcomeType $masterOutcomeType): bool
    {
        return $masterOutcomeType->project_id ? $user->belongsToProject($masterOutcomeType->project_id) : $user->id === $masterOutcomeType->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MasterOutcomeType $masterOutcomeType): bool
    {
        return $masterOutcomeType->project_id ? $user->belongsToProject($masterOutcomeType->project_id) : $user->id === $masterOutcomeType->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasterOutcomeType $masterOutcomeType): bool
    {
        return $masterOutcomeType->project_id ? $user->belongsToProject($masterOutcomeType->project_id) : $user->id === $masterOutcomeType->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasterOutcomeType $masterOutcomeType): bool
    {
        return $masterOutcomeType->project_id ? $user->belongsToProject($masterOutcomeType->project_id) : $user->id === $masterOutcomeType->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasterOutcomeType $masterOutcomeType): bool
    {
        return $masterOutcomeType->project_id ? $user->belongsToProject($masterOutcomeType->project_id) : $user->id === $masterOutcomeType->user_id;
    }
}
