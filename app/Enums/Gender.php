<?php

namespace App\Enums;

use App\Traits\RandomEnumCase;

enum Gender: string {
    use RandomEnumCase;

    case Male = 'male';
    case Female = 'female';

    /**
     * @return string
     */
    public function getName(): string
    {
        return match ($this->name) {
            'Male' => 'Man',
            'Female' => 'Vrouw'
        };
    }
}
