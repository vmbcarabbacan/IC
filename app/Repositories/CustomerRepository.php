<?php

namespace App\Repositories;

use App\Interfaces\CustomerInterface;
use App\Models\CarDriverDetail;
use App\Models\Customer;
use App\Models\CustomerDetails;
use App\Services\CarLeadService;
use Illuminate\Support\Str;

class CustomerRepository extends CarLeadService implements CustomerInterface {

    public function getCustomer(array $condition, array $with = [])
    {
        return $this->model(Customer::class, $condition, $with);
    }

    public function createOrUpdate(array $data)
    {
        $customer = new Customer();

        if(!isset($data['session_id']))
            $data['session_id'] = $this->randomString();

        $exist = $this->getCustomer(['country_code' => $data['country_code'], 'phone_number' => $data['phone_number'] ]);
        if($exist) $data['id'] = $exist->id;

        if(isset($data['id']) && $data['id'] > 0)
        $customer = $this->getCustomer(['id' => $data['id'] ]);

        $customer->fill($data);
        $customer->save();

        unset($data['id']);
        $data['customer_id'] = $customer->id;
        $detail = $this->createOrUpdateDetails($data);
        
        $hasQLNewOrPending = $this->checkIfCustomerHavingNewOrPending($customer->id);

        if($hasQLNewOrPending)
            $data['id'] = $hasQLNewOrPending->driver->id;

        $driver = $this->createOrUpdateDriver($data);
        
        if($hasQLNewOrPending)
            $data['id'] = $hasQLNewOrPending->id;

        $data['customer_detail_id'] = $detail->id;
        $data['car_driver_detail_id'] = $driver->id;
        $this->createOrUpLead($data);

        return $customer;
    }

    private function createOrUpdateDetails(array $data) {
        $details = new CustomerDetails();

        $exist = $this->model(
            CustomerDetails::class,
            ['customer_id' => $data['customer_id'], 'insurance_type' => $data['insurance_type'] ]
        );

        if($exist) {
            $details = CustomerDetails::where('id', $exist->id)->first();
            if(isset($data['agent_id']))
                unset($data['agent_id']);
        }

        $details->fill($data);
        $details->save();

        return $details;
    }


    

}