<?php

namespace App\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Events\CarLeadStatusEvent;
// use App\Events\CustomerStatusEvent;
use App\Models\CarDriverDetail;
use App\Services\GlobalService;
use App\Models\CarLead;

class CarDriverDetailObserver extends GlobalService implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the CarDriverDetail "created" event.
     */
    public function created(CarDriverDetail $carDriverDetail): void
    {
        //
    }

    /**
     * Handle the CarDriverDetail "updated" event.
     */
    public function updated(CarDriverDetail $carDriverDetail): void
    {
        $lead = $this->model(CarLead::class, ['car_driver_detail_id' => $carDriverDetail->id]);
        if($carDriverDetail->wasChanged()) {
            event(new CarLeadStatusEvent($lead->id, ['has_changed' => $this->getPendingLead()]));
            // event(new CustomerStatusEvent($lead->customer_id, $this->getCar(), ['customer_return' => true]));
        }
            

    }

    /**
     * Handle the CarDriverDetail "deleted" event.
     */
    public function deleted(CarDriverDetail $carDriverDetail): void
    {
        //
    }

    /**
     * Handle the CarDriverDetail "restored" event.
     */
    public function restored(CarDriverDetail $carDriverDetail): void
    {
        //
    }

    /**
     * Handle the CarDriverDetail "force deleted" event.
     */
    public function forceDeleted(CarDriverDetail $carDriverDetail): void
    {
        //
    }
}
