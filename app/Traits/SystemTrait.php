<?php

namespace App\Traits;

trait SystemTrait {
    
    public function system() {
        return 1;
    }

    // Customer Status
    public function getNewNotContacted() {
        return 1;
    }

    public function getReturnNotContacted() {
        return 2;
    }

    public function getReturnFollowUp() {
        return 3;
    }

    public function getCustomers() {
        return 4;
    }

    public function getPotentialCustomer() {
        return 5;
    }

    public function getCustomerNotContacted() {
        return 6;
    }

    public function getCustomerFollowUp() {
        return 7;
    }

    public function getPartialCustomer() {
        return 8;
    }

    public function getLostPontentialCustomerNotContacted() {
        return 9;
    }

    public function getLostPontentialCustomerFollowUp() {
        return 10;
    }

    public function getLostCustomer() {
        return 11;
    }

    public function getNewFollowUp() {
        return 12;
    }

    public function getPartialCustomerNotContacted() {
        return 13;
    }

    public function getPartialCustomerFollowUp() {
        return 14;
    }

    public function getLostCustomerNotContacted() {
        return 15;
    }

    public function getLostCustomerFollowUp() {
        return 16;
    }

    // customer status in array
    public function getCustomerStatus() {
        return [4, 6, 7];
    }

    public function getLostCustomerStatus() {
        return [11, 15, 16];
    }

    public function getLostPotentialCustomerStatus() {
        return [5, 9, 10];
    }

    // Lead Status
    public function getNewLead() {
        return 1;
    }

    public function getQuickLead() {
        return 2;
    }
    
    public function getPendingLead() {
        return 3;
    }

    public function getDealLead() {
        return 4;
    }

    public function getRenewalLead() {
        return 5;
    }

    public function getLostLeadLead() {
        return 6;
    }

    public function getLostLeadRenewalLead() {
        return 7;
    }

    public function getLeadRenewalLostLead() {
        return 8;
    }

    public function getDealTransferedLead() {
        return 9;
    }
    
    public function getSystemQuickLead() {
        return 10;
    }
    
    public function getFalseLead() {
        return 11;
    }

    public function getCancelledLead() {
        return 12;
    }

    public function getIntransactLead() {
        return 13;
    }

    public function getClosedLeaad() {
        return 14;
    }

    public function getRedundantLead() {
        return 15;
    }

    public function getQLNewAndPending() {
        return [1, 2, 3];
    }

    // Start Task Status
    public function getLostLeadDisposition()
    {
        return 8;
    }
    public function getCustomerBoughtDisposition()
    {
        return 9;
    }

    public function getCallbackDisposition()
    {
        return 10;
    }

    public function getCallbackPolicyCancelledDisposition()
    {
        return 12;
    }
    public function getCallbackIntransactDisposition()
    {
        return 13;
    }

    public function getClosedDispositionDisposition()
    {
        return 19;
    }

    public function getCallbackRefundClosedDisposition()
    {
        return 20;
    }
    public function getTaskOpenStatusDisposition()
    {
        return 1;
    }
    public function getTaskCloseStatusDisposition()
    {
        return 2;
    }

    public function getFalseLeadDispositionDisposition()
    {
        return 11;
    }

    public function getPolicyTransferedFromTaskStatusDisposition()
    {
        return 14;
    }

    public function getPolicyTransferedToTaskStatusDisposition()
    {
        return 15;
    }
    public function getRenewalCallbackTaskStatusDisposition()
    {
        return 16;
    }
    public function getRedundantLeaDispositiond() {
        return 17;
    }
    /**
     * jira issue 465
     */
    public function getAddNewLeadDisposition() {
        return 18;
    }

    // insurance type
    public function getCar() {
        return 1;
    }
}