<?php

namespace Qihucms\Information\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Qihucms\Information\Models\InformationMessage;

class SendMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $informationMessage;

    /**
     * Create a new event instance.
     *
     * @param InformationMessage $informationMessage
     * @return void
     */
    public function __construct(InformationMessage $informationMessage)
    {
        $this->informationMessage = $informationMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('information.message.' . $this->informationMessage->user_id);
    }
}
