<?php

namespace App\Actions\Fortify;

use App\Enums\Gender;
use App\Models\Residence;
use App\Models\User;
use App\Models\UserProfile;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:40'],
            'middlename' => ['nullable', 'string', 'max:40'],
            'lastname' => ['required', 'string', 'max:40'],
            'birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string'],
            'residence' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        if (!Gender::tryFrom($input['gender']))
            throw ValidationException::withMessages(['gender' => 'Geslacht is niet geldig.']);

        $birthdate = new DateTime($input['birthdate']);
        $age = (new DateTime())->diff($birthdate)->y;
        if ($age < 18)
            throw ValidationException::withMessages(['birthdate' => 'Je moet 18 jaar of ouder zijn om dit app te kunnen gebruiken.']);

        $user = User::create([
            'firstname' => $input['firstname'],
            'middlename' => $input['middlename'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $residenceInput = ucfirst($input['residence']);
        $residence = Residence::firstWhere('residence', '=', $residenceInput)
            ?? Residence::create(['residence' => $residenceInput]);


        UserProfile::create([
            'user_id' => $user->id,
            'birthdate' => $input['birthdate'],
            'gender' => $input['gender'],
            'phone_number' => $input['phone_number'],
            'residence_id' => $residence->id
        ]);

        return $user;
    }
}
