<?php

namespace App\Livewire;

use App\Enums\Gender;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UpdateProfilePreference extends Component
{
    public $state = [];

    public function mount(): void
    {
        $preference = Auth::user()->preference;

        $this->state = array_merge($preference->withoutRelations()->toArray());
    }

    public function updateProfilePreference()
    {
        $input = Validator::make($this->state, [
            'gender' => ['nullable', 'string'],
            'minimum_age' => ['nullable', 'integer', 'min:18'],
            'maximum_age' => ['nullable', 'integer', 'min:18']
        ])->validate();

        $ageClamped = $input['minimum_age'] && $input['maximum_age'];
        if ($ageClamped && $input['maximum_age'] < $input['minimum_age'])
            throw ValidationException::withMessages(['maximum_age' => 'Maximum moet groter of gelijk zijn dan de minimum.']);

        $gender = Gender::tryFrom(strtolower($input['gender']));

        Auth::user()->preference->update([
            'gender' => $gender,
            'minimum_age' => !$input['minimum_age'] ? null : $input['minimum_age'],
            'maximum_age' => !$input['maximum_age'] ? null : $input['maximum_age']
        ]);
    }

    public function getUserProperty(): User
    {
        return Auth::user();
    }

    public function render()
    {
        return view('profile.update-profile-preference');
    }
}
