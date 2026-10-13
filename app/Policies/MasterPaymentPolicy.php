<?php

namespace App\Policies;

use App\Models\MasterPayment;
use App\Models\User;

class MasterPaymentPolicy
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
    public function view(User $user, MasterPayment $masterPayment): bool
    {
        return $masterPayment->project_id ? $user->belongsToProject($masterPayment->project_id) : $user->id === $masterPayment->user_id;
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
    public function update(User $user, MasterPayment $masterPayment): bool
    {
        return $masterPayment->project_id ? $user->belongsToProject($masterPayment->project_id) : $user->id === $masterPayment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasterPayment $masterPayment): bool
    {
        return $masterPayment->project_id ? $user->belongsToProject($masterPayment->project_id) : $user->id === $masterPayment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasterPayment $masterPayment): bool
    {
        return $masterPayment->project_id ? $user->belongsToProject($masterPayment->project_id) : $user->id === $masterPayment->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasterPayment $masterPayment): bool
    {
        return $masterPayment->project_id ? $user->belongsToProject($masterPayment->project_id) : $user->id === $masterPayment->user_id;
    }
}
