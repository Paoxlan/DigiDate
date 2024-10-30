<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'bio',
        'study',
        'birthdate',
        'gender',
        'phone_number',
        'residence_id'
    ];

    public function age(): Attribute {

        return Attribute::make(
            get: fn ($_, array $attributes) => (new DateTime())
                ->diff(new DateTime($attributes['birthdate']))
                ->y
        );

    }

    public function residence(): BelongsTo
    {
        return $this->belongsTo(Residence::class);
    }
}
