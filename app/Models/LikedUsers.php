<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikedUsers extends Model
{
    protected $table = 'liked_users';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'liked_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedUser()
    {
        return $this->belongsTo(User::class, 'liked_user_id');
    }
}
