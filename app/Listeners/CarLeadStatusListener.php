<?php

namespace App\Listeners;

use App\Events\CarLeadStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\CarLeadService;

class CarLeadStatusListener extends CarLeadService
{
    /**
     * Handle the event.
     */
    public function handle(CarLeadStatusEvent $event): void
    {
        //
        $car_lead_id = $event->car_lead_id;
        $data = $event->data;

        $car_lead = $this->getCarLead([ 'id' =>  $car_lead_id]);
        
        $car_lead->status = $this->getStatus($car_lead, $data);
        $car_lead->save();
    } 

    private function getStatus($car_lead, $data = null) {
        
        $status = 0;

        /**
         * Scenario 1 :Users enters Vehicle Details and Driver details and Reached Quotes page
         * Scenario 2 : Users enters Vehicle Details different car as Scenario 1 and leaves website
         * Scenario 3 : Users enters Vehicle Details different car as Scenario 1 and leaves website
         */
        if($car_lead->status == 0)
            $status = $this->getNewLead();

        /**
         * Scenario 4: Quick Lead entered ( When agent add the Lead Details )
         */
        if($car_lead->driver->brand_id == 0)
            $status = $this->getQuickLead();

        /*
        *  Scenario 5: Agent calls user and actions on a task for the lead
        *  Scenario 18: After deal is lost Quick Lead entered
        *  Scenario 26: Quick Lead entered
        *  Scenario 34: Quick Lead entered
        *  Quick lead merge with  lead
        */
        else if(isset($data['has_changed']) && $data['has_changed'] == $this->getPendingLead())
            $status = $this->getPendingLead();
        
        /*
        *  Scenario 39: After scenario 6, when agent closes deal and customer sells car and insurance is transferred to new owner then for old owner a quick lead will get added.
        */
        else if(isset($data['has_changed']) && $data['has_changed'] == $this->getDealTransferedLead())
            $status = $this->getDealTransferedLead();

        /*
        *  Scenario 66 : If the Lead is been Lost for two years continuously
           On Yes Mark the lead Status is Closed
        */
        else if(isset($data['has_changed']) && $data['has_changed'] == $this->getClosedLeaad())
            $status = $this->getClosedLeaad();
        
        /*
        *  Scenario 42: In addition for scenario 39, a quick lead will be added for the (old) lost customer
        */
        else if(isset($data['has_changed']) && $data['has_changed'] == $this->getSystemQuickLead())
            $status = $this->getSystemQuickLead();

        /*
        *  Scenario 42: A user/customer enters false/ dummy Lead data -- False Lead
        *  Scenario 65: A user/customer/agent enters duplicate Lead data -- REDUNDANT LEAD
        */
        else if($data && isset($data['has_changed']) && in_array($data['has_changed'],[$this->getFalseLead(),$this->getRedundantLead()]))
        {
            $status = $data['has_changed'];
        }
        else {
            /**
             * Scenario 5: Agent calls user
             */
            $status = 1;
        }

        return $status;

    }
}
