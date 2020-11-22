<?php

namespace Qihucms\Information\Resources;

use App\Http\Resources\User\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Qihucms\Information\Models\InformationMessage;

class Friend extends JsonResource
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
            'friend' => new User($this->friend),
            'friend_name' => $this->friend_name,
            'status' => $this->status,
            'information_messages_count' => $this->information_messages_count ?: 0,
            'information_messages_first' => $this->information_messages_count ? $this->first_message() : null,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }

    /**
     * 首条信息
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    protected function first_message()
    {
        $item = InformationMessage::select('type', 'message')->where('information_friend_id', $this->id)
            ->latest()->first();

        if ($item) {
            // 如果不是文字信息，显示消息类型
            if ($item->type) {
                return trans('information::information_message.type.value.' . $item->type);
            }

            return $item->message;
        }

        return null;
    }
}
