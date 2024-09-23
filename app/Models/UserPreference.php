<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public $timestamps = false;
    protected $primaryKey = "user_id";

    protected $fillable = [
        'gender',
        'minimum_age',
        'maximum_age'
    ];
}
