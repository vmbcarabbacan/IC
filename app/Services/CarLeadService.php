<?php

namespace App\Services;
use App\Models\CarLead;
use App\Models\CarDriverDetail;
// use App\Services\GlobalService;
use App\Services\CarLeadTaskService;

class CarLeadService extends CarLeadTaskService {

    public function createOrUpLead(array $data) {
        $lead = new CarLead();

        if(isset($data['id']) && $data['id'] > 0)
            $lead = CarLead::where('id', $data['id'])->first();

        if(isset($data['name']) && isset($data['driver_name']) && empty($data['driver_name']))
            $data['driver_name'] = $data['name'];
        
        $lead->fill($data);
        $lead->save();

        return $lead;
    } 
    
    protected function createOrUpdateDriver(array $data) {
        $driver = new CarDriverDetail();

        if(isset($data['id']) && $data['id'] > 0)
            $driver = CarDriverDetail::where('id', $data['id'])->first();

        $driver->fill($data);
        $driver->save();

        return $driver;
    }

    public function getCarLead($condtion) {
        return CarLead::where($condtion)
        ->first();
    }

    public function checkIfCustomerHavingNewOrPending($customer_id) {
        return CarLead::with(['driver'])
        ->where('customer_id', $customer_id)
        ->whereIn('status', $this->getQLNewAndPending())
        ->first();
    }

}