<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stringable;

class Residence extends Model implements Stringable
{
    use HasFactory;
    protected $fillable = [
        'residence'
    ];

    public function __toString(): string
    {
        return $this->residence;
    }
}
