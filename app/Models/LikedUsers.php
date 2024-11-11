<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class LikedUsers extends Model
{
    protected $table = 'liked_users';

    public $timestamps = false;

    protected $fillable = [
        'from_id',
        'to_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function likedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    protected function casts(): array
    {
        return ['is_liked' => 'bool'];
    }

    /**
     * @param User $from
     * @return LikedUsers[]|Collection
     */
    public static function getLikedUsersFrom(User $from): array|Collection
    {
        return self::where('from_id', $from->id)->get();
    }

    public static function userIsMatchedWith(User $user, User $toUser): bool
    {
        $to = self::where([
            ['from_id', $user->id],
            ['to_id', $toUser->id]
        ])->first();

        $from = self::where([
            ['from_id', $toUser->id],
            ['to_id', $user->id]
        ])->first();

        return $to?->is_liked && $from?->is_liked;
    }
}
