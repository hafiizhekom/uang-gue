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
        activity('master_payment')
        ->causedBy(auth()->user())
        ->performedOn($masterPayment)
        ->withProperties([
            'action' => 'Add Wallet / Metode Pembayaran',
            'name'   => $masterPayment->name,
            'balance' => $masterPayment->balance,
        ])
        ->log('created');
        
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
        activity('master_payment')
        ->causedBy(auth()->user())
        ->performedOn($masterPayment)
        ->withProperties([
            'action' => 'Update Wallet /Metode Pembayaran',
            'before' => $masterPayment->getOriginal(),
            'after'  => $masterPayment->getChanges(),
        ])
        ->log('updated');
        
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
        activity('master_payment')
        ->causedBy(auth()->user())
        ->performedOn($masterPayment)
        ->withProperties([
            'action' => 'Delete Walet / Metode Pembayaran',
            'name'   => $masterPayment->name,
            'balance' => $masterPayment->balance,
        ])
        ->log('deleted');
        
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
        activity('master_payment')
        ->causedBy(auth()->user())
        ->performedOn($masterPayment)
        ->withProperties([
            'action' => 'Restore Wallet / Metode Pembayaran',
            'name'   => $masterPayment->name,
            'balance' => $masterPayment->balance,
        ])
        ->log('restored');
        
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
        activity('master_payment')
        ->causedBy(auth()->user())
        ->performedOn($masterPayment)
        ->withProperties([
            'action' => 'Permanently Deleted Wallet / Metode Pembayaran',
            'name'   => $masterPayment->name,
            'balance' => $masterPayment->balance,
        ])
        ->log('force_deleted');

        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterPayment',
            'id'      => $masterPayment->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
