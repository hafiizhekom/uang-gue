<?php

namespace App\Observers;

use App\Models\MasterPayment;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class MasterPaymentObserver
{
    use ClearDashboardCache;
    /**
     * Handle the MasterPayment "created" event.
     */
    public function created(MasterPayment $masterPayment): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPayment "updated" event.
     */
    public function updated(MasterPayment $masterPayment): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPayment "deleted" event.
     */
    public function deleted(MasterPayment $masterPayment): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPayment "restored" event.
     */
    public function restored(MasterPayment $masterPayment): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the MasterPayment "force deleted" event.
     */
    public function forceDeleted(MasterPayment $masterPayment): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
