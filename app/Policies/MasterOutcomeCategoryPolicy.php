<?php

namespace App\Policies;

use App\Models\MasterOutcomeCategory;
use App\Models\User;

class MasterOutcomeCategoryPolicy
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
    public function view(User $user, MasterOutcomeCategory $masterOutcomeCategory): bool
    {
        return $masterOutcomeCategory->project_id ? $user->belongsToProject($masterOutcomeCategory->project_id) : $user->id === $masterOutcomeCategory->user_id;
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
    public function update(User $user, MasterOutcomeCategory $masterOutcomeCategory): bool
    {
        return $masterOutcomeCategory->project_id ? $user->belongsToProject($masterOutcomeCategory->project_id) : $user->id === $masterOutcomeCategory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasterOutcomeCategory $masterOutcomeCategory): bool
    {
        return $masterOutcomeCategory->project_id ? $user->belongsToProject($masterOutcomeCategory->project_id) : $user->id === $masterOutcomeCategory->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasterOutcomeCategory $masterOutcomeCategory): bool
    {
        return $masterOutcomeCategory->project_id ? $user->belongsToProject($masterOutcomeCategory->project_id) : $user->id === $masterOutcomeCategory->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasterOutcomeCategory $masterOutcomeCategory): bool
    {
        return $masterOutcomeCategory->project_id ? $user->belongsToProject($masterOutcomeCategory->project_id) : $user->id === $masterOutcomeCategory->user_id;
    }
}
