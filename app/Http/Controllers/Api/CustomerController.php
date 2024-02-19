<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CustomerInterface;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;
use App\Traits\LoggingTrait;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use ResponseTrait, LoggingTrait;

    protected $customerInterface;

    public function __construct(CustomerInterface $customerInterface)
    {
        $this->customerInterface = $customerInterface;
    }

    public function addCustomer(Request $request) {
        $rules = [
            'country_code' => 'required',
            'phone_number' => 'required',
            'name' => 'required',
            'insurance_type' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) return $this->resValidation('Add customer validation failure', $validator->errors());

        try {
            DB::beginTransaction();
            $customer = $this->customerInterface->createOrUpdate($data);

            DB::commit();
            return $this->resSuccess('Customer added/updated successfully', $customer);
        } catch (\Exception $e) {
            $this->logError([
                "function" => "addCustomer",
                "data" => $e
            ]);
            DB::rollBack();
        }
        
    }
}
