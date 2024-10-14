<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "user_id";

    protected $fillable = [
        'user_id',
        'gender',
        'minimum_age',
        'maximum_age'
    ];
}
