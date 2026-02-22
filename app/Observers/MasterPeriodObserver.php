<?php

namespace App\Observers;

use App\Models\MasterPeriod;

class MasterPeriodObserver
{
    /**
     * Handle the MasterPeriod "created" event.
     */
    public function created(MasterPeriod $masterPeriod): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterPeriod "updated" event.
     */
    public function updated(MasterPeriod $masterPeriod): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterPeriod "deleted" event.
     */
    public function deleted(MasterPeriod $masterPeriod): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterPeriod "restored" event.
     */
    public function restored(MasterPeriod $masterPeriod): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterPeriod "force deleted" event.
     */
    public function forceDeleted(MasterPeriod $masterPeriod): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterPeriod',
            'id'      => $masterPeriod->id,
            'user_id' => auth()->id(),
        ]);
    }
}
