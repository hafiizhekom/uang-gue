<?php

namespace App\Observers;

use App\Models\MasterPeriod;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class MasterPeriodObserver
{
    use ClearDashboardCache;
    /**
     * Handle the MasterPeriod "created" event.
     */
    public function created(MasterPeriod $masterPeriod): void
    {
        //
        activity('master_period')
        ->causedBy(auth()->user())
        ->performedOn($masterPeriod)
        ->withProperties([
            'action' => 'Add Period',
            'name'   => $masterPeriod->name,
            'start_date' => $masterPeriod->start_date,
            'end_date' => $masterPeriod->end_date,
        ])
        ->log('created');

        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPeriod "updated" event.
     */
    public function updated(MasterPeriod $masterPeriod): void
    {
        //
        activity('master_period')
        ->causedBy(auth()->user())
        ->performedOn($masterPeriod)
        ->withProperties([
            'action' => 'Update Period',
            'before' => $masterPeriod->getOriginal(),
            'after'  => $masterPeriod->getChanges(),
        ])
        ->log('updated');
        
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPeriod "deleted" event.
     */
    public function deleted(MasterPeriod $masterPeriod): void
    {
        //
        activity('master_period')
        ->causedBy(auth()->user())
        ->performedOn($masterPeriod)
        ->withProperties([
            'action' => 'Delete Period',
            'name'   => $masterPeriod->name,
            'start_date' => $masterPeriod->start_date,
            'end_date' => $masterPeriod->end_date,
        ])
        ->log('deleted');
        
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPeriod "restored" event.
     */
    public function restored(MasterPeriod $masterPeriod): void
    {
        //
        activity('master_period')
        ->causedBy(auth()->user())
        ->performedOn($masterPeriod)
        ->withProperties([
            'action' => 'Restore Period',
            'name'   => $masterPeriod->name,
            'start_date' => $masterPeriod->start_date,
            'end_date' => $masterPeriod->end_date,
        ])
        ->log('restored');
        
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPeriod "force deleted" event.
     */
    public function forceDeleted(MasterPeriod $masterPeriod): void
    {
        //
        activity('master_period')
        ->causedBy(auth()->user())
        ->performedOn($masterPeriod)
        ->withProperties([
            'action' => 'Permanently Deleted Period',
            'name'   => $masterPeriod->name,
            'start_date' => $masterPeriod->start_date,
            'end_date' => $masterPeriod->end_date,
        ])
        ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
