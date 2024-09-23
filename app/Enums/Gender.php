<?php

namespace App\Enums;

enum Gender: string {
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
