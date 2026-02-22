<?php

namespace App\Observers;

use App\Models\MasterOutcomeHutang;

class MasterOutcomeHutangObserver
{
    /**
     * Handle the MasterOutcomeHutang "created" event.
     */
    public function created(MasterOutcomeHutang $masterOutcomeHutang): void
    {
        //
        Log::channel('observer')->info("Data Created via Observer", [
            'model'   => 'MasterOutcomeHutang',
            'id'      => $masterOutcomeHutang->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterOutcomeHutang "updated" event.
     */
    public function updated(MasterOutcomeHutang $masterOutcomeHutang): void
    {
        //
        Log::channel('observer')->info("Data Updated via Observer", [
            'model'   => 'MasterOutcomeHutang',
            'id'      => $masterOutcomeHutang->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterOutcomeHutang "deleted" event.
     */
    public function deleted(MasterOutcomeHutang $masterOutcomeHutang): void
    {
        //
        Log::channel('observer')->info("Data Deleted via Observer", [
            'model'   => 'MasterOutcomeHutang',
            'id'      => $masterOutcomeHutang->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterOutcomeHutang "restored" event.
     */
    public function restored(MasterOutcomeHutang $masterOutcomeHutang): void
    {
        //
        Log::channel('observer')->info("Data Restored via Observer", [
            'model'   => 'MasterOutcomeHutang',
            'id'      => $masterOutcomeHutang->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Handle the MasterOutcomeHutang "force deleted" event.
     */
    public function forceDeleted(MasterOutcomeHutang $masterOutcomeHutang): void
    {
        //
        Log::channel('observer')->info("Data Force Deleted via Observer", [
            'model'   => 'MasterOutcomeHutang',
            'id'      => $masterOutcomeHutang->id,
            'user_id' => auth()->id(),
        ]);
    }
}
