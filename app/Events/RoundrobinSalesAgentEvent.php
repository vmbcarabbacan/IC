<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundrobinSalesAgentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer_id, $agent_id;

    /**
     * Create a new event instance.
     */
    public function __construct($customer_id = null, $agent_id = null)
    {
        $this->customer_id = $customer_id;
        $this->agent_id = $agent_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
