<?php

namespace Qihucms\Information\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FriendCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
