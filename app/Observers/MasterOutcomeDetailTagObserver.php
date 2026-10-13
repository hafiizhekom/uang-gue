<?php

namespace App\Observers;

use App\Models\MasterOutcomeDetailTag;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class MasterOutcomeDetailTagObserver
{
    use ClearDashboardCache;

    /**
     * Handle the MasterOutcomeDetailTag "created" event.
     */
    public function created(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        activity('master_outcome_detail_tag')
            ->causedBy(auth()->user())
            ->performedOn($masterOutcomeDetailTag)
            ->tap(fn($act) => $act->project_id = $masterOutcomeDetailTag->project_id)
            ->withProperties([
                'action' => 'Add Outcome Detail Tag',
                'name'   => $masterOutcomeDetailTag->name,
            ])
            ->log('created');
        
        Log::channel('observer')->info("Data Created via Observer", [
            'model'      => 'MasterOutcomeDetailTag',
            'id'         => $masterOutcomeDetailTag->id,
            'project_id' => $masterOutcomeDetailTag->project_id,
            'user_id'    => auth()->id(),
        ]);

        $this->refreshDashboard($masterOutcomeDetailTag->project_id);
    }

    /**
     * Handle the MasterOutcomeDetailTag "updated" event.
     */
    public function updated(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        activity('master_outcome_detail_tag')
            ->causedBy(auth()->user())
            ->performedOn($masterOutcomeDetailTag)
            ->tap(fn($act) => $act->project_id = $masterOutcomeDetailTag->project_id)
            ->withProperties([
                'action' => 'Update Outcome Detail Tag',
                'before' => $masterOutcomeDetailTag->getOriginal(),
                'after'  => $masterOutcomeDetailTag->getChanges(),
            ])
            ->log('updated');
        
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'      => 'MasterOutcomeDetailTag',
            'id'         => $masterOutcomeDetailTag->id,
            'project_id' => $masterOutcomeDetailTag->project_id,
            'user_id'    => auth()->id(),
        ]);

        $this->refreshDashboard($masterOutcomeDetailTag->project_id);
    }

    /**
     * Handle the MasterOutcomeDetailTag "deleted" event.
     */
    public function deleted(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        activity('master_outcome_detail_tag')
            ->causedBy(auth()->user())
            ->performedOn($masterOutcomeDetailTag)
            ->tap(fn($act) => $act->project_id = $masterOutcomeDetailTag->project_id)
            ->withProperties([
                'action' => 'Delete Outcome Detail Tag',
                'name'   => $masterOutcomeDetailTag->name,
            ])
            ->log('deleted');

        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'      => 'MasterOutcomeDetailTag',
            'id'         => $masterOutcomeDetailTag->id,
            'project_id' => $masterOutcomeDetailTag->project_id,
            'user_id'    => auth()->id(),
        ]);

        $this->refreshDashboard($masterOutcomeDetailTag->project_id);
    }

    /**
     * Handle the MasterOutcomeDetailTag "restored" event.
     */
    public function restored(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        activity('master_outcome_detail_tag')
            ->causedBy(auth()->user())
            ->performedOn($masterOutcomeDetailTag)
            ->tap(fn($act) => $act->project_id = $masterOutcomeDetailTag->project_id)
            ->withProperties([
                'action' => 'Restore Outcome Detail Tag',
                'name'   => $masterOutcomeDetailTag->name,
            ])
            ->log('restored');
        
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'      => 'MasterOutcomeDetailTag',
            'id'         => $masterOutcomeDetailTag->id,
            'project_id' => $masterOutcomeDetailTag->project_id,
            'user_id'    => auth()->id(),
        ]);

        $this->refreshDashboard($masterOutcomeDetailTag->project_id);
    }

    /**
     * Handle the MasterOutcomeDetailTag "force deleted" event.
     */
    public function forceDeleted(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        activity('master_outcome_detail_tag')
            ->causedBy(auth()->user())
            ->performedOn($masterOutcomeDetailTag)
            ->tap(fn($act) => $act->project_id = $masterOutcomeDetailTag->project_id)
            ->withProperties([
                'action' => 'Permanently Deleted Outcome Detail Tag',
                'name'   => $masterOutcomeDetailTag->name,
            ])
            ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'      => 'MasterOutcomeDetailTag',
            'id'         => $masterOutcomeDetailTag->id,
            'project_id' => $masterOutcomeDetailTag->project_id,
            'user_id'    => auth()->id(),
        ]);

        $this->refreshDashboard($masterOutcomeDetailTag->project_id);
    }
}
