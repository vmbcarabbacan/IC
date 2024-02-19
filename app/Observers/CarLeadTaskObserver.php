<?php

namespace App\Observers;

use App\Models\CarLead;
use App\Models\CarLeadTask;
use App\Models\CustomerDetails;
use App\Services\GlobalService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class CarLeadTaskObserver extends GlobalService implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the CarLeadTask "created" event.
     */
    public function created(CarLeadTask $carLeadTask): void
    {
        $customerDetails = $this->model(CustomerDetails::class, ['customer_id' => $carLeadTask->customer_id]);
        $lead = $this->model(CarLead::class, ['id' => $carLeadTask->car_lead_id]);

        $carLeadTask->created_by = $this->currentUser();
        $carLeadTask->agent_id = $customerDetails->agent_id;
        $carLeadTask->agent_id = $customerDetails->agent_id;
        $carLeadTask->customer_status_id = $customerDetails->status;
        $carLeadTask->lead_status_id = $lead->status;

        $carLeadTask->saveQuietly();

        $customerDetails->task_due_date = $carLeadTask->due_date;
        $customerDetails->is_renewal = $this->isRenewal($carLeadTask->customer_id);
        $customerDetails->task_count = $this->getCount($carLeadTask->customer_id);
        $customerDetails->saveQuietly();
    }

    /**
     * Handle the CarLeadTask "updated" event.
     */
    public function updated(CarLeadTask $carLeadTask): void
    {
        $customerDetails = $this->model(CustomerDetails::class, ['customer_id' => $carLeadTask->customer_id]);
        
        if($carLeadTask->wasChanged()) 
            $carLeadTask->updated_by = $this->currentUser();

        $carLeadTask->saveQuietly();

        $customerDetails->task_due_date = $carLeadTask->due_date;
        $customerDetails->is_renewal = $this->isRenewal($carLeadTask->customer_id);
        $customerDetails->task_count = $this->getCount($carLeadTask->customer_id);
        $customerDetails->saveQuietly();
    }

    /**
     * Handle the CarLeadTask "deleted" event.
     */
    public function deleted(CarLeadTask $carLeadTask): void
    {
        $carLeadTask->deleted_by = $this->currentUser();
        $carLeadTask->saveQuietly();
    }

    /**
     * Handle the CarLeadTask "restored" event.
     */
    public function restored(CarLeadTask $carLeadTask): void
    {
        //
    }

    /**
     * Handle the CarLeadTask "force deleted" event.
     */
    public function forceDeleted(CarLeadTask $carLeadTask): void
    {
        //
    }

    private function getCount($customer_id) {
        return CarLeadTask::where(['customer_id' => $customer_id])->whereNull('closed_at')->count();
    }

    private function isRenewal($customer_id) {
        return CarLeadTask::where(['customer_id' => $customer_id, 'is_renewal' => 1])->whereNull('closed_at')->exists();
    }
}
