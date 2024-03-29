<?php

namespace App\Services;
use App\Models\CarLeadTask;
use App\Models\CustomerDetails;
use App\Services\GlobalService;
use Illuminate\Support\Facades\DB;

class CarLeadTaskService extends GlobalService {

    public function addTask($data) {
        
        try {
            DB::beginTransaction();
            if(!isset($data['due_date']))
            $data['due_date'] = $this->systemDate()->addMinutes(5)->format('Y-m-d H:i:s');


            if(isset($data['is_renewal'])) {
                $data['disposition_id'] = $this->getRenewalCallbackTaskStatusDisposition(); // Renewal Callback
                $data['is_renewal'] = true;
            }
                

            $data['disposition_id'] = isset($data['disposition_id']) ? $data['disposition_id'] : $this->getCallbackDisposition();

            $data['closed_at'] = isset($data['closed_at']) ? $data['closed_at'] : null;

            $task = new CarLeadTask();
            $task->fill($data);
            $task->save();

            DB::commit();

            return $task;
        } catch(\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function assignUnsignedAgent() {
        $tasks = CarLeadTask::where('agent_id', 0)->get();

        foreach($tasks as $task) {
            $customer = CustomerDetails::where([
                'customer_id' => $task->customer_id,
                'insurance_type' => $this->getCar()
            ])->first();

            $task->agent_id = $customer->agent_id;
            $task->save();
        }
    }
    
    public function getCount($customer_id) {
        return CarLeadTask::where(['customer_id' => $customer_id])->whereNull('closed_at')->count();
    }

    public function isRenewal($customer_id) {
        return CarLeadTask::where(['customer_id' => $customer_id, 'is_renewal' => 1])->whereNull('closed_at')->exists();
    }

    public function getOldestDueDate($customer_id) {
        return CarLeadTask::where(['customer_id' => $customer_id])->whereNull('closed_at')->orderBy('due_date', 'ASC')->first()->due_date;

    }

}