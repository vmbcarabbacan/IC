<?php

namespace App\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\CustomerDetails;
use App\Traits\SystemTrait;
use App\Events\CustomerLogEvent;

class CustomerDetailObserver implements ShouldHandleEventsAfterCommit
{
    use SystemTrait;
    /**
     * Handle the CustomerDetails "created" event.
     */
    public function created(CustomerDetails $customerDetails): void
    {
        if($customerDetails->status == 0) {
            $customerDetails->status = $this->getNewNotContacted();

            $data = [
                'current_data' => config('constants.customer_log')[$this->getNewNotContacted()],
            ];
            event(new CustomerLogEvent('update-customer-status', $customerDetails->customer_id, $data));
        }

        if($customerDetails->agent_id == 0)
            // function for roundrobin

        $customerDetails->saveQuietly();
    }

    /**
     * Handle the CustomerDetails "updated" event.
     */
    public function updated(CustomerDetails $customerDetails): void
    {
        if($customerDetails->wasChanged('status')) {
            $customerDetails->status_old = $customerDetails->getOriginal('status');

            $data = [
                'current_data' => config('constants.customer_log')[$customerDetails->status],
                'previous_data' => config('constants.customer_log')[$customerDetails->getOriginal('status')]
            ];
            event(new CustomerLogEvent('update-customer-status', $customerDetails->customer_id, $data));
        }
            

        $customerDetails->saveQuietly();
    }

    /**
     * Handle the CustomerDetails "deleted" event.
     */
    public function deleted(CustomerDetails $customerDetails): void
    {
        //
    }

    /**
     * Handle the CustomerDetails "restored" event.
     */
    public function restored(CustomerDetails $customerDetails): void
    {
        //
    }

    /**
     * Handle the CustomerDetails "force deleted" event.
     */
    public function forceDeleted(CustomerDetails $customerDetails): void
    {
        //
    }
}
