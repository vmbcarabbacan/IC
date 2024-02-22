<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\LogService;
use App\Models\CustomerLog;
use App\Services\GlobalService;

class CustomerLogListener extends GlobalService
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $type = $event->type;
        $customer_id = $event->customer_id;
        $data = $event->data;
        
        $number = 1;
        switch($type) {
            case 'updated-customer':
                $number = 2; break;
            case 'update-customer-status':
                $number = 3; break;
            case 'agent-assigned';
                $number = 4; break;

            default: $number = 1; break;
        }

        $data['title'] = config('constants.customer_log')[$number];
        $data['id'] = $this->currentUser();
        $data['by'] = $this->currentUserName();
        $data['date'] = $this->systemNow();

        $log = new CustomerLog();
        $log->customer_id = $customer_id;
        $log->type = $type;
        $log->log = $data;
        $log->save();

    }
}
