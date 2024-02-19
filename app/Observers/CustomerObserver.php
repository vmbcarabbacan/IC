<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\GlobalService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Events\CustomerLogEvent;

class CustomerObserver extends GlobalService implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $customer->created_by = $this->currentUser();
        $customer->country_code = $this->countryCodeCheck($customer->country_code);
        $customer->phone_number = $this->phoneNumberCheck($customer->phone_number);
        $customer->complete_phone_number = $customer->country_code . $customer->phone_number;

        $currentData = $customer->name . ' | ' . $customer->country_code . ' - ' . $customer->phone_number;
        if($customer->email) $currentData .= ' | ' . $customer->email;
        if($customer->other_contact_info) $currentData .= $customer->other_contact_info;

        $data = [ 'current_data' => $currentData ];

        event(new CustomerLogEvent('create-customer', $customer->id, $data));
        
        $customer->saveQuietly();

    }

    /**
     * Handle the Customer "updating" event.
     */
    public function updating(Customer $customer): void
    {
        $customer->country_code = $this->countryCodeCheck($customer->country_code);
        $customer->phone_number = $this->phoneNumberCheck($customer->phone_number);
        $customer->complete_phone_number = $customer->country_code . $customer->phone_number;

        if($customer->wasChanged('name') || $customer->wasChanged('phone_number') || $customer->wasChanged('country_code') || $customer->wasChanged('email') || $customer->wasChanged('other_contact_info')) {
            $customer->updated_by = $this->currentUser();
        
            $oldData = $customer->getOriginal('name') . ' | ' . $customer->getOriginal('country_code') . ' - ' . $customer->getOriginal('phone_number');
            if($customer->getOriginal('email')) $oldData .= ' | ' . $customer->getOriginal('email');
            if($customer->getOriginal('other_contact_info')) $oldData .=  ' | ' . $customer->getOriginal('other_contact_info');

            $currentData = $customer->name . ' | ' . $customer->country_code . ' - ' . $customer->phone_number;
            if($customer->email) $currentData .= ' | ' . $customer->email;
            if($customer->other_contact_info) $currentData .=  ' | ' . $customer->other_contact_info;

            $data = [
                'previous_data' => $oldData,
                'current_data' => $currentData
            ];

            event(new CustomerLogEvent('updated-customer', $customer->id, $data));
        }

        $customer->saveQuietly();
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        $customer->deleted_by = $this->currentUser();
        $customer->saveQuietly();
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
