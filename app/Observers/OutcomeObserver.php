<?php

namespace App\Observers;

use App\Models\Outcome;
use App\Traits\ClearDashboardCache;
use Illuminate\Support\Facades\Log;

class OutcomeObserver
{
    use ClearDashboardCache;
    /**
     * Handle the Outcome "created" event.
     */
    public function created(Outcome $outcome): void
    {
        //
        activity('outcome')
        ->causedBy(auth()->user())
        ->performedOn($outcome)
        ->withProperties([
            'action' => 'Add Outcome',
            'period' => $outcome->period ? $outcome->period->name : null,
            'title'  => $outcome->title,
            'amount' => $outcome->amount,
            'category' => $outcome->category ? $outcome->category->name : null,
            'type' => $outcome->type ? $outcome->type->name : null,
            'payment' => $outcome->payment ? $outcome->payment->name : null,
            'note'   => $outcome->note,
            'date'   => $outcome->date,
        ])
        ->log('created');
        
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "updated" event.
     */
    public function updated(Outcome $outcome): void
    {
        //
        activity('outcome')
        ->causedBy(auth()->user())
        ->performedOn($outcome)
        ->withProperties([
            'action' => 'Update Outcome',
            'before' => $outcome->getOriginal(),
            'after'  => $outcome->getChanges(),
        ])
        ->log('updated');
        
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "deleted" event.
     */
    public function deleted(Outcome $outcome): void
    {
        //
        activity('outcome')
        ->causedBy(auth()->user())
        ->performedOn($outcome)
        ->withProperties([
            'action' => 'Delete Outcome',
            'period' => $outcome->period ? $outcome->period->name : null,
            'title'  => $outcome->title,
            'amount' => $outcome->amount,
            'category' => $outcome->category ? $outcome->category->name : null,
            'type' => $outcome->type ? $outcome->type->name : null,
            'payment' => $outcome->payment ? $outcome->payment->name : null,
            'note'   => $outcome->note,
            'date'   => $outcome->date,
        ])
        ->log('deleted');
        
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "restored" event.
     */
    public function restored(Outcome $outcome): void
    {
        //
        activity('outcome')
        ->causedBy(auth()->user())
        ->performedOn($outcome)
        ->withProperties([
            'action' => 'Restore Outcome',
            'period' => $outcome->period ? $outcome->period->name : null,
            'title'  => $outcome->title,
            'amount' => $outcome->amount,
            'category' => $outcome->category ? $outcome->category->name : null,
            'type' => $outcome->type ? $outcome->type->name : null,
            'payment' => $outcome->payment ? $outcome->payment->name : null,
            'note'   => $outcome->note,
            'date'   => $outcome->date,
        ])
        ->log('restored');
        
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }

    /**
     * Handle the Outcome "force deleted" event.
     */
    public function forceDeleted(Outcome $outcome): void
    {
        //
        activity('outcome')
        ->causedBy(auth()->user())
        ->performedOn($outcome)
        ->withProperties([
            'action' => 'Permanently Deleted Outcome',
            'period' => $outcome->period ? $outcome->period->name : null,
            'title'  => $outcome->title,
            'amount' => $outcome->amount,
            'category' => $outcome->category ? $outcome->category->name : null,
            'type' => $outcome->type ? $outcome->type->name : null,
            'payment' => $outcome->payment ? $outcome->payment->name : null,
            'note'   => $outcome->note,
            'date'   => $outcome->date,
        ])
        ->log('force_deleted');
        
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'Outcome',
            'id'      => $outcome->id,
            'user_id' => auth()->id(),
        ]);

        $this->refreshDashboard(auth()->id());
    }
}
