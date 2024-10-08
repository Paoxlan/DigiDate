<?php

namespace Database\Factories;

use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'bio' => $this->faker->sentence,
            'birthdate' => $this->faker->date,
            'gender' => "male",
            'phone_number' => $this->faker->phoneNumber,
            'residence_id' => 1
        ];
    }
}
