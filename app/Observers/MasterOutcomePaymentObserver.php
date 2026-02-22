<?php

namespace App\Observers;

use App\Models\MasterOutcomePayment;

class MasterOutcomePaymentObserver
{
    /**
     * Handle the MasterOutcomePayment "created" event.
     */
    public function created(MasterOutcomePayment $masterOutcomePayment): void
    {
        //
    }

    /**
     * Handle the MasterOutcomePayment "updated" event.
     */
    public function updated(MasterOutcomePayment $masterOutcomePayment): void
    {
        //
    }

    /**
     * Handle the MasterOutcomePayment "deleted" event.
     */
    public function deleted(MasterOutcomePayment $masterOutcomePayment): void
    {
        //
    }

    /**
     * Handle the MasterOutcomePayment "restored" event.
     */
    public function restored(MasterOutcomePayment $masterOutcomePayment): void
    {
        //
    }

    /**
     * Handle the MasterOutcomePayment "force deleted" event.
     */
    public function forceDeleted(MasterOutcomePayment $masterOutcomePayment): void
    {
        //
    }
}
