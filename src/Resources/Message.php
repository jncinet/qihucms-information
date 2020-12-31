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
            'user_id' => $this->user_id,
            'information_friend' => new Friend($this->information_friend),
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
