<?php

namespace App\Observers;

use App\Models\Outcome;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class OutcomeObserver
{
    use ClearDashboardCache;
    /**
     * Handle the Outcome "created" event.
     */
    public function created(Outcome $outcome): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "updated" event.
     */
    public function updated(Outcome $outcome): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "deleted" event.
     */
    public function deleted(Outcome $outcome): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "restored" event.
     */
    public function restored(Outcome $outcome): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "force deleted" event.
     */
    public function forceDeleted(Outcome $outcome): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
