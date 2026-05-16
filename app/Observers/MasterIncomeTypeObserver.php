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
        activity('master_income_type')
        ->causedBy(auth()->user())
        ->performedOn($masterIncomeType)
        ->withProperties([
            'action' => 'Add Income Type',
            'name'   => $masterIncomeType->name,
        ])
        ->log('created');
        
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
        activity('master_income_type')
        ->causedBy(auth()->user())
        ->performedOn($masterIncomeType)
        ->withProperties([
            'action' => 'Update Income Type',
            'before' => $masterIncomeType->getOriginal(),
            'after'  => $masterIncomeType->getChanges(),
        ])
        ->log('updated');

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
        activity('master_income_type')
        ->causedBy(auth()->user())
        ->performedOn($masterIncomeType)
        ->withProperties([
            'action' => 'Delete Income Type',
            'name'   => $masterIncomeType->name,
        ])
        ->log('deleted');
        
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
        activity('master_income_type')
        ->causedBy(auth()->user())
        ->performedOn($masterIncomeType)
        ->withProperties([
            'action' => 'Restore Income Type',
            'name'   => $masterIncomeType->name,
        ])
        ->log('restored');
        
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
        activity('master_income_type')
        ->causedBy(auth()->user())
        ->performedOn($masterIncomeType)
        ->withProperties([
            'action' => 'Permanently Deleted Income Type',
            'name'   => $masterIncomeType->name,
        ])
        ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterIncomeType',
            'id'      => $masterIncomeType->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
