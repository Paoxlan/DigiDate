<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model implements \Stringable
{
    use HasFactory;
    protected $fillable = ['name'];

    public function __toString(): string
    {
        return $this->name;
    }
}
