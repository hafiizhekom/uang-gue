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
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterOutcomeCategory',
            'id'      => $masterOutcomeCategory->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
