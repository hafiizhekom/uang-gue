<?php

namespace App\Observers;

use App\Models\Income;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class IncomeObserver
{
    use ClearDashboardCache;
    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Income "restored" event.
     */
    public function restored(Income $income): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Income "force deleted" event.
     */
    public function forceDeleted(Income $income): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
