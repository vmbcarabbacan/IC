<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshTokenEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $user_id, $data, $is_valid;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $data, $is_valid = true)
    {
        $this->user_id = $user_id;
        $this->data = $data;
        $this->is_valid = $is_valid;
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->data,
            'is_valid' => $this->is_valid
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new channel('refresh-token-' . $this->user_id),
        ];
    }
}
