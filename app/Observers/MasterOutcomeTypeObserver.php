<?php

namespace App\Observers;

use App\Models\MasterOutcomeType;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class MasterOutcomeTypeObserver
{
    use ClearDashboardCache;
    /**
     * Handle the MasterOutcomeType "created" event.
     */
    public function created(MasterOutcomeType $masterOutcomeType): void
    {
        //
        activity('master_outcome_type')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeType)
        ->withProperties([
            'action' => 'Add Outcome Type',
            'name'   => $masterOutcomeType->name,
        ])
        ->log('created');
        
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterOutcomeType',
            'id'      => $masterOutcomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeType "updated" event.
     */
    public function updated(MasterOutcomeType $masterOutcomeType): void
    {
        //
        activity('master_outcome_type')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeType)
        ->withProperties([
            'action' => 'Update Outcome Type',
            'before' => $masterOutcomeType->getOriginal(),
            'after'  => $masterOutcomeType->getChanges(),
        ])
        ->log('updated');
        
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterOutcomeType',
            'id'      => $masterOutcomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeType "deleted" event.
     */
    public function deleted(MasterOutcomeType $masterOutcomeType): void
    {
        //
        activity('master_outcome_type')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeType)
        ->withProperties([
            'action' => 'Delete Outcome Type',
            'name'   => $masterOutcomeType->name,
        ])
        ->log('deleted');
        
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterOutcomeType',
            'id'      => $masterOutcomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeType "restored" event.
     */
    public function restored(MasterOutcomeType $masterOutcomeType): void
    {
        //
        activity('master_outcome_type')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeType)
        ->withProperties([
            'action' => 'Restore Outcome Type',
            'name'   => $masterOutcomeType->name,
        ])
        ->log('restored');

        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterOutcomeType',
            'id'      => $masterOutcomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeType "force deleted" event.
     */
    public function forceDeleted(MasterOutcomeType $masterOutcomeType): void
    {
        //
        activity('master_outcome_type')
        ->causedBy(auth()->user())
        ->performedOn($masterOutcomeType)
        ->withProperties([
            'action' => 'Permanently Deleted Outcome Type',
            'name'   => $masterOutcomeType->name,
        ])
        ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterOutcomeType',
            'id'      => $masterOutcomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
