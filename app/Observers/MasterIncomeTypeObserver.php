<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\MasterIncomeType;
use App\Traits\ClearDashboardCache;

class MasterIncomeTypeObserver
{
    use ClearDashboardCache;
    /**
     * Handle the MasterIncomeType "created" event.
     */
    public function created(MasterIncomeType $masterIncomeType): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterIncomeType "updated" event.
     */
    public function updated(MasterIncomeType $masterIncomeType): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterIncomeType "deleted" event.
     */
    public function deleted(MasterIncomeType $masterIncomeType): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterIncomeType "restored" event.
     */
    public function restored(MasterIncomeType $masterIncomeType): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterIncomeType "force deleted" event.
     */
    public function forceDeleted(MasterIncomeType $masterIncomeType): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
