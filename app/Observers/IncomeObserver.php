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
        activity('income')
        ->causedBy(auth()->user())
        ->performedOn($income)
        ->withProperties([
            'action'  => 'Add Income',
            'amount'  => $income->amount,
            'title'   => $income->title,
            'date'    => $income->date,
            'note'    => $income->note,
        ])
        ->log('created');
        
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
        activity('income')
        ->causedBy(auth()->user())
        ->performedOn($income)
        ->withProperties([
            'action' => 'Update Income',
            'amount' => $income->amount,
            'before' => $income->getOriginal(), // data sebelum diubah
            'after'  => $income->getChanges(),  // data setelah diubah
        ])
        ->log('updated');

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
        activity('income')
        ->causedBy(auth()->user())
        ->performedOn($income)
        ->withProperties([
            'action' => 'Delete Income',
            'amount' => $income->amount,
            'title'  => $income->title,
        ])
        ->log('deleted');

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
        activity('income')
        ->causedBy(auth()->user())
        ->performedOn($income)
        ->withProperties([
            'action' => 'Restore Income',
            'amount' => $income->amount,
            'title'  => $income->title,
        ])
        ->log('restored');

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
        activity('income')
        ->causedBy(auth()->user())
        ->performedOn($income)
        ->withProperties([
            'action' => 'Permanently Deleted Income',
            'amount' => $income->amount,
            'title'  => $income->title,
        ])
        ->log('force_deleted');

        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'Income',
            'id'      => $income->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
