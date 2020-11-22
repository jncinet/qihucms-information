<?php

namespace Qihucms\Information\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Qihucms\Information\Models\InformationFriend;

class AddFriend
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $informationFriend;

    /**
     * Create a new event instance.
     *
     * @param InformationFriend $informationFriend
     * @return void
     */
    public function __construct(InformationFriend $informationFriend)
    {
        $this->informationFriend = $informationFriend;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('information.friend.' . $this->informationFriend->user_id);
    }
}
