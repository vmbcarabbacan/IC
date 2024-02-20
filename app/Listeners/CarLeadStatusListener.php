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

        if($car_lead->status == 0)
            $status = $this->getNewLead();

        if($car_lead->driver->brand_id == 0)
            $status = $this->getQuickLead();

        else if(isset($data['has_changed']))
            $status = $data['has_changed'];
        
        else {
            /**
             * Scenario 5: Agent calls user
             */
            $status = 1;
        }

        return $status;

    }
}
