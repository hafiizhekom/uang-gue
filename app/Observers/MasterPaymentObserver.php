<?php

namespace App\Observers;

use App\Models\MasterPayment;

class MasterPaymentObserver
{
    /**
     * Handle the MasterPayment "created" event.
     */
    public function created(MasterPayment $masterPayment): void
    {
        //
    }

    /**
     * Handle the MasterPayment "updated" event.
     */
    public function updated(MasterPayment $masterPayment): void
    {
        //
    }

    /**
     * Handle the MasterPayment "deleted" event.
     */
    public function deleted(MasterPayment $masterPayment): void
    {
        //
    }

    /**
     * Handle the MasterPayment "restored" event.
     */
    public function restored(MasterPayment $masterPayment): void
    {
        //
    }

    /**
     * Handle the MasterPayment "force deleted" event.
     */
    public function forceDeleted(MasterPayment $masterPayment): void
    {
        //
    }
}
