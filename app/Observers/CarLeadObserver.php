<?php

namespace App\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Events\CarLeadStatusEvent;
use App\Events\CustomerStatusEvent;
use App\Models\CarLead;
use App\Services\GlobalService;
use App\Models\CarDriverDetail;

class CarLeadObserver extends GlobalService implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the CarLead "created" event.
     */
    public function created(CarLead $carLead): void
    {
        event(new CarLeadStatusEvent($carLead->id, null));
    }

    /**
     * Handle the CarLead "updated" event.
     */
    public function updated(CarLead $carLead): void
    {
        if($carLead->wasChanged('status'))
            $carLead->status_old = $carLead->getOriginal('status');

        event(new CustomerStatusEvent($carLead->id, $this->getCar()));

        $carLead->saveQuietly();
    }

    /**
     * Handle the CarLead "deleted" event.
     */
    public function deleted(CarLead $carLead): void
    {
        //
    }

    /**
     * Handle the CarLead "restored" event.
     */
    public function restored(CarLead $carLead): void
    {
        //
    }

    /**
     * Handle the CarLead "force deleted" event.
     */
    public function forceDeleted(CarLead $carLead): void
    {
        //
    }
}
