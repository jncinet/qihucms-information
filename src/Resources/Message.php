<?php

namespace Qihucms\Information\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'from_user' => new User($this->from_user),
            'to_user' => new User($this->to_user),
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
