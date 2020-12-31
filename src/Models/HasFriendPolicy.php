<?php

namespace Qihucms\Information\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasFriendPolicy
{
    /**
     * @return HasOne
     */
    public function FriendPolicy(): HasOne
    {
        return $this->hasOne('Qihucms\Information\Models\InformationFriendPolicy', 'user_id');
    }
}