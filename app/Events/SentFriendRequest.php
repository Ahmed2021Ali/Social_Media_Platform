<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SentFriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $user_id;
    public function __construct($name,$user_id)
    {
        $this->name = $name;
        $this->user_id = $user_id;
    }

    public function broadcastOn()
    {
        return ['chat'];
    }
    public function broadcastAs()
    {
        return ''.$this->user_id.'';
    }
}
