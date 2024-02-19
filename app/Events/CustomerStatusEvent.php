<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $customer_id, $insurance_type, $data;

    /**
     * Create a new event instance.
     */
    public function __construct($customer_id, $insurance_type, $data = null)
    {
        $this->customer_id = $customer_id;
        $this->insurance_type = $insurance_type;
        $this->data = $data;
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
