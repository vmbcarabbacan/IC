<?php

namespace App\Listeners;

use App\Events\CustomerStatusEvent;
use App\Models\CarLead;
use App\Models\CustomerDetails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\SystemTrait;

class CustomerStatusListener
{
    use SystemTrait;
    /**
     * Handle the event.
     */
    public function handle(CustomerStatusEvent $event): void
    {
        $customer_id = $event->customer_id;
        $insurance_type = $event->insurance_type;
        $data = $event->data;

        $customer = CustomerDetails::where([
            'customer_id' => $customer_id,
            'insurance_type' => $insurance_type
        ])->first();

        $customer->status = $this->getStatus($customer_id, $customer->status, $data);
        $customer->save();
    }

    private function getStatus($customer_id, $status_old, $data = null) {
        
        $all_count = $this->leads($customer_id)->count();
        $new_count = $this->leads($customer_id, [$this->getQuickLead(), $this->getNewLead()])->count();
        $pending_count = $this->leads($customer_id, [$this->getPendingLead()])->count();
        $deal_count = $this->leads($customer_id, [$this->getDealLead()])->count();
        $renewal_count = $this->leads($customer_id, [$this->getRenewalLead()])->count();
        $lead_renewal_lost_count = $this->leads($customer_id, [$this->getLeadRenewalLostLead()])->count();
        $lost_lead_count = $this->leads($customer_id, [$this->getLostLeadLead()])->count();
        $lost_lead_renewal_count = $this->leads($customer_id, [$this->getLostLeadRenewalLead()])->count();
        $cancelled_count = $this->leads($customer_id, [$this->getCancelledLead()])->count();
   
        $is_renewal = $this->leads($customer_id, [$this->getPendingLead()], ['status_old' => $this->getRenewalLead()])->count();
        $is_lost_lead_renewal = $this->leads($customer_id, [$this->getPendingLead()], ['status_old' => $this->getLostLeadLead()])->count();
        $is_lead_renewal_lost = $this->leads($customer_id, [$this->getPendingLead()], ['status_old' => $this->getLostLeadRenewalLead()])->count();

        $is_customer = in_array($status_old, $this->getCustomerStatus());
        $is_lost_customer = in_array($status_old, $this->getLostCustomerStatus());
        $is_lost_potential_customer = in_array($status_old, $this->getLostPotentialCustomerStatus());

        $status = $this->getNewNotContacted();
        if($pending_count > 0) $status = $this->getNewFollowUp();
        if($new_count > 0) $status = $this->getNewNotContacted();

        // skip Return
        if(isset($data['customer_return']) && $data['customer_return'] && $status_old > 0){
            $status = $this->getReturnNotContacted();
            if($pending_count > 0) $newStatus = $this->getReturnFollowUp();
            if($new_count > 0) $newStatus = $this->getReturnNotContacted();
        }

        if($deal_count > 0) {
            $status = $this->getCustomers();
            if($pending_count > 0) $status = $this->getCustomerFollowUp();
            if($new_count > 0) $status = $this->getCustomerNotContacted();
            if($all_count == $deal_count) $status = $this->getCustomers();
        }

        if($renewal_count > 0 || $is_renewal || $is_customer) {
            $status = $this->getCustomerNotContacted();
            if($is_renewal || $is_customer) $status = $this->getCustomerFollowUp();
            if($all_count == $deal_count) $status = $this->getCustomers();
        }

        if($lost_lead_count > 0 || $lost_lead_renewal_count > 0 || $cancelled_count > 0 || $is_lost_lead_renewal || $is_lost_customer) {
            $status = $this->getLostCustomer();
            if($pending_count > 0) $status = $this->getLostPontentialCustomerNotContacted();
            if($new_count > 0 || $is_lost_lead_renewal || $is_lost_customer) $status = $this->getLostPontentialCustomerFollowUp();
        }

        if($lead_renewal_lost_count > 0 || $is_lead_renewal_lost || $is_lost_potential_customer) {
            $status = $this->getLostCustomer();
            if($pending_count > 0) $status = $this->getLostCustomerNotContacted();
            if($new_count > 0 || $is_lead_renewal_lost || $is_lost_potential_customer) $status = $this->getLostCustomerFollowUp();
        }

        if(($deal_count > 0 || $renewal_count > 0 || $is_renewal || $is_customer) && ($lost_lead_count > 0 || $lead_renewal_lost_count > 0 || $lost_lead_renewal_count > 0 || $cancelled_count > 0 || $is_lost_lead_renewal || $is_lead_renewal_lost || $is_lost_customer || $is_lost_potential_customer)) {
            $status = $this->getPartialCustomer();
            if($pending_count > 0 || $is_renewal || $is_lost_lead_renewal || $is_lead_renewal_lost || $is_customer || $is_lost_customer || $is_lost_potential_customer) $status = $this->getPartialCustomerFollowUp();
            if($new_count > 0 || $renewal_count > 0) $status = $this->getPartialCustomerNotContacted();
        }

        return $status;
    }

    private function leads($customer_id, $status = null, $condition = null) {
        $leads = CarLead::where('customer_id', $customer_id);

        if($condition)
            $leads = $leads->where($condition);

        if($status)
            $leads = $leads->whereIn('status', $status);

        return $leads;
    }
}
