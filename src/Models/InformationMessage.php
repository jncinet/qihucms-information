<?php

namespace Qihucms\Information\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qihucms\Information\Events\SendMessage;

class InformationMessage extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'information_friend_id', 'user_id', 'type', 'message', 'status'
    ];

    /**
     * 发布消息时，好友关系表更新updated_at时间戳
     *
     * @var array
     */
    protected $touches = ['information_friend'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => SendMessage::class,
    ];

    /**
     * @return BelongsTo
     */
    public function information_friend(): BelongsTo
    {
        return $this->belongsTo('Qihucms\Information\Models\InformationFriend');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}