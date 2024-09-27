<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stringable;

class Residence extends Model implements Stringable
{
    protected $fillable = [
        'residence'
    ];

    public function __toString(): string
    {
        return $this->residence;
    }
}
