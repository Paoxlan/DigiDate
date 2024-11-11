<?php

namespace App\Actions\Fortify;

use App\Enums\Gender;
use App\Models\AuditTrail;
use App\Models\Residence;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserProfile;
use App\Traits\AuditTrailable;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, AuditTrailable;

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

        $gender = Gender::tryFrom($input['gender']);
        if (!$gender) throw ValidationException::withMessages(['gender' => 'Geslacht is niet geldig.']);

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

        $residence = Residence::firstWhere('residence', '=', $residenceInput);
        if (!$residence) {
            $residence = Residence::create(['residence' => $residenceInput]);
            AuditTrail::create([
                'class' => Residence::class,
                'method' => 'Create',
                'model' => $this->auditTrailJson($residence)
            ]);
        }

        $userProfile = UserProfile::create([
            'user_id' => $user->id,
            'birthdate' => $input['birthdate'],
            'gender' => $gender,
            'phone_number' => $input['phone_number'],
            'residence_id' => $residence->id
        ]);

        $userPreferences = UserPreference::create([
            'user_id' => $user->id,
            'gender' => $gender === Gender::Male ? Gender::Female : Gender::Male
        ]);

        AuditTrail::create([
            'user_id' => $user->id,
            'method' => 'Create',
            'class' => User::class,
            'model' => $this->auditTrailJson([$user, $userProfile, $userPreferences])
        ]);

        return $user;
    }

    protected function getHiddenAuditTrailAttributes(): array
    {
        return [
            'user_id',
            'profile_photo_url',
            'updated_at'
        ];
    }
}
