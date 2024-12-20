<?php

namespace App\Observers;

use App\Models\People;

class PeopleObserver
{
    /**
     * Handle the People "created" event.
     */
    public function created(People $people): void
    {
        //
    }

    /**
     * Handle the People "updated" event.
     */
    public function updated(People $people): void
    {
        //
    }

    /**
     * Handle the People "deleting" event.
     */
    public function deleting(People $people): void
    {
        if ($people->photos()->exists()) { //@phpstan-ignore-line
            foreach ($people->photos as $photo) { //@phpstan-ignore-line
                $photo->delete();
            }
        }
    }

    /**
     * Handle the People "restored" event.
     */
    public function restored(People $people): void
    {
        //
    }

    /**
     * Handle the People "force deleted" event.
     */
    public function forceDeleted(People $people): void
    {
        //
    }
}
