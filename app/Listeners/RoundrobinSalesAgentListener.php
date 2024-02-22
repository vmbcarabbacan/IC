<?php

namespace App\Listeners;

use App\Events\CustomerLogEvent;
use App\Events\RoundrobinSalesAgentEvent;
use App\Models\CarLeadTask;
use App\Models\CustomerDetails;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\GlobalService;
use Illuminate\Support\Facades\DB;

class RoundrobinSalesAgentListener extends GlobalService implements ShouldQueue
{


    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RoundrobinSalesAgentEvent $event): void
    {
        $customer_id = $event->customer_id;
        $agent_id = $event->agent_id;

        $customers = $this->getCustomersUnAssigned($customer_id);
        foreach($customers as $customer) {
            $customer_id = $customer->id;

            if($agent_id) $customer->agent_id = $agent_id; 
            else {
                $setting = $this->getSetting($this->getSettingValue($customer->insurance_type));
                if($setting) {
                    $agents = $this->getAgentsByInsuranceType($customer->insurance_type);
                    $agent_id = $this->getSalesAgents($agents, $setting->value);
                    $customer->agent_id = $agent_id;

                    $this->updateConfiguration($this->getSettingValue($customer->insurance_type), $agent_id);
                }
            }
            
            $customer->save();

            $this->updateAgentTask($customer_id, $agent_id);

            $user = User::find($agent_id);
            event(new CustomerLogEvent('agent-assigned', $customer_id, ['current_data' => $user->name ]));
        }
    }

    private function getCustomersUnAssigned($customer_id = null) {
        $customer = CustomerDetails::where('agent_id', 0);

        if($customer_id) $customer = $customer->where('customer_id', $customer_id);

        return $customer->get();
    }

    private function getAgentsByInsuranceType($insurance_type) {
        return UserSetting::whereIn('role_id', $this->getAgentRoundrobinRoles())
                ->where([
                    'insurance_type' => $insurance_type,
                    'is_round_robin' => 1,
                    'status' => 1
                ])
                ->where('agent_type', '<>', 2) // new and both
                ->get();
    }

    private function getSalesAgents($agents, $last_id) {
        $count = count($agents);

        $key = array_search($last_id, array_column($agents->toArray(), 'user_id'));
        $key++;

        if($key >= $count || $last_id == 0)
            $key = 0;
        
        return $agents[$key]['user_id'];       

    }

    private function getSettingValue($insurance_type) {
        switch($insurance_type) {
            case 1: 
            case 5: return 'last_car_agent_assigned';
            case 2: 
            case 6: return 'last_health_agent_assigned';
            case 3: return 'last_tavel_agent_assigned';
            case 4: return 'last_home_agent_assigned';
            default: return '';
        }
    }

    private function updateConfiguration($key, $value) {
        DB::table('app_configurations')->where('key', $key)->update([
            'value' => $value
        ]);
    }

    private function updateAgentTask($customer_id, $agent_id) {
        CarLeadTask::where([
            'customer_id' => $customer_id,
            'agent_id' => 0
        ])
        ->update([
            'agent_id' => $agent_id
        ]);
    }
}
