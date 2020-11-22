<?php

namespace Qihucms\Information\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformationFriendPolicy extends Model
{
    protected $fillable = ['user_id', 'question', 'answer', 'password'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}