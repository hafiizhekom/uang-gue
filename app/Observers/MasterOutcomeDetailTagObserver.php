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
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterOutcomeDetailTag',
            'id'      => $masterOutcomeDetailTag->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeDetailTag "updated" event.
     */
    public function updated(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterOutcomeDetailTag',
            'id'      => $masterOutcomeDetailTag->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeDetailTag "deleted" event.
     */
    public function deleted(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterOutcomeDetailTag',
            'id'      => $masterOutcomeDetailTag->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeDetailTag "restored" event.
     */
    public function restored(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterOutcomeDetailTag',
            'id'      => $masterOutcomeDetailTag->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterOutcomeDetailTag "force deleted" event.
     */
    public function forceDeleted(MasterOutcomeDetailTag $masterOutcomeDetailTag): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterOutcomeDetailTag',
            'id'      => $masterOutcomeDetailTag->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
