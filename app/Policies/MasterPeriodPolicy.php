<?php

namespace App\Policies;

use App\Models\MasterPeriod;
use App\Models\User;

class MasterPeriodPolicy
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
    public function view(User $user, MasterPeriod $masterPeriod): bool
    {
        return $masterPeriod->project_id ? $user->belongsToProject($masterPeriod->project_id) : $user->id === $masterPeriod->user_id;
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
    public function update(User $user, MasterPeriod $masterPeriod): bool
    {
        return $masterPeriod->project_id ? $user->belongsToProject($masterPeriod->project_id) : $user->id === $masterPeriod->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasterPeriod $masterPeriod): bool
    {
        return $masterPeriod->project_id ? $user->belongsToProject($masterPeriod->project_id) : $user->id === $masterPeriod->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasterPeriod $masterPeriod): bool
    {
        return $masterPeriod->project_id ? $user->belongsToProject($masterPeriod->project_id) : $user->id === $masterPeriod->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasterPeriod $masterPeriod): bool
    {
        return $masterPeriod->project_id ? $user->belongsToProject($masterPeriod->project_id) : $user->id === $masterPeriod->user_id;
    }
}
