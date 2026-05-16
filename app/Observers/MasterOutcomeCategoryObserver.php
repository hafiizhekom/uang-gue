<?php

namespace App\Observers;

use App\Models\MasterOutcomeCategory;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class MasterOutcomeCategoryObserver
{
    use ClearDashboardCache;
    /**
     * Handle the MasterOutcomeCategory "created" event.
     */
    public function created(MasterOutcomeCategory $masterOutcomeCategory): void
    {
        //
        activity('master_outcome_category')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeCategory)
        ->withProperties([
            'action' => 'Add Outcome Category',
            'name'   => $masterOutcomeCategory->name,
        ])
        ->log('created');
        
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);
        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeCategory "updated" event.
     */
    public function updated(MasterOutcomeCategory $masterOutcomeCategory): void
    {
        //
        activity('master_outcome_category')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeCategory)
        ->withProperties([
            'action' => 'Update Outcome Category',
            'before' => $masterOutcomeCategory->getOriginal(),
            'after'  => $masterOutcomeCategory->getChanges(),
        ])
        ->log('updated');
        
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);
        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeCategory "deleted" event.
     */
    public function deleted(MasterOutcomeCategory $masterOutcomeCategory): void
    {
        //
        activity('master_outcome_category')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeCategory)
        ->withProperties([
            'action' => 'Delete Outcome Category',
            'name'   => $masterOutcomeCategory->name,
        ])
        ->log('deleted');
        
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);
        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeCategory "restored" event.
     */
    public function restored(MasterOutcomeCategory $masterOutcomeCategory): void
    {
        //
        activity('master_outcome_category')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeCategory)
        ->withProperties([
            'action' => 'Restore Outcome Category',
            'name'   => $masterOutcomeCategory->name,
        ])
        ->log('restored');
        
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeCategory "force deleted" event.
     */
    public function forceDeleted(MasterOutcomeCategory $masterOutcomeCategory): void
    {
        //
        activity('master_outcome_category')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeCategory)
        ->withProperties([
            'action' => 'Permanently Deleted Outcome Category',
            'name'   => $masterOutcomeCategory->name,
        ])
        ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
