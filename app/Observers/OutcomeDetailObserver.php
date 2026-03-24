<?php

namespace App\Observers;

use App\Models\OutcomeDetail;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class OutcomeDetailObserver
{
    use ClearDashboardCache;
    /**
     * Handle the OutcomeDetail "created" event.
     */
    public function created(OutcomeDetail $outcome_detail): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'OutcomeDetail',
            'id'      => $outcome_detail->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the OutcomeDetail "updated" event.
     */
    public function updated(OutcomeDetail $outcome_detail): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'OutcomeDetail',
            'id'      => $outcome_detail->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the OutcomeDetail "deleted" event.
     */
    public function deleted(OutcomeDetail $outcome_detail): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'OutcomeDetail',
            'id'      => $outcome_detail->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the OutcomeDetail "restored" event.
     */
    public function restored(OutcomeDetail $outcome_detail): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'OutcomeDetail',
            'id'      => $outcome_detail->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the OutcomeDetail "force deleted" event.
     */
    public function forceDeleted(OutcomeDetail $outcome_detail): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'OutcomeDetail',
            'id'      => $outcome_detail->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
