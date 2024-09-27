<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        $passwordRule = Password::min(8);
        if (!app()->hasDebugModeEnabled())
            $passwordRule = $passwordRule->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

        return [
            'required',
            'string',
            $passwordRule,
            'confirmed'
        ];
    }
}
