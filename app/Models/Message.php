<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function getSendTime(): string
    {
        if (!$this->created_at) return "";

        $date = $this->created_at->format('Y-m-d');
        $currentDate = (new \DateTime())->format('Y-m-d');
        if ($date === $currentDate) return $this->created_at->format('H:i');

        return $this->created_at->format('M-d');
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Message[]|Collection
     */
    public static function GetMessagesFrom(User $user1, User $user2): array|Collection
    {
        return self::where([
            ['sender_id', $user1->id],
            ['receiver_id', $user2->id]
        ])->orWhere([
            ['receiver_id', $user1->id],
            ['sender_id', $user2->id]
        ])->get();
    }
}
