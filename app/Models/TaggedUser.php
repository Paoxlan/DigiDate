<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaggedUser extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = [
        'user_id',
        'tag_id'
    ];

    protected $fillable = [
        'user_id',
        'tag_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery(mixed $keyName = null): mixed
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public static function userHasTag(User $user, Tag|int $tag): bool
    {
        $tagId = is_int($tag) ? $tag : $tag->id;
        return !is_null(self::where('user_id', $user->id)
            ->where('tag_id', $tagId)->first());
    }
}
