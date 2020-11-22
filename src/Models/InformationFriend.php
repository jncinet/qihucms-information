<?php

namespace Qihucms\Information\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Qihucms\Information\Events\AddFriend;

class InformationFriend extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'friend_id', 'friend_name', 'status'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => AddFriend::class,
    ];

    /**
     * @return HasMany
     */
    public function information_messages(): HasMany
    {
        return $this->hasMany('Qihucms\Information\Models\InformationMessage');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}