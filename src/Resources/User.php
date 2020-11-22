<?php

namespace Qihucms\Information\Resources;

use App\Services\PhotoService;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (empty($this->avatar)) {
            $avatar = \Cache::get('config_default_avatar');
        } else {
            $avatar = $this->avatar;
        }
        $avatar = (new PhotoService())->getImgUrl($avatar, 56);

        return [
            'id' => $this->id,
            'nickname' => empty($this->nickname) ? '游客' : $this->nickname,
            'avatar' => $avatar,
        ];
    }
}