<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_id;

    public function __construct($message,$user_id)
    {
        $this->message = $message;
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
