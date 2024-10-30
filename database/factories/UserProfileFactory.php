<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\UserProfile;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{

    protected $model = UserProfile::class;

    public function definition(): array
    {
        $dateInterval = new DateInterval('P18Y');
        return [
            'bio' => $this->faker->sentence,
            'birthdate' => $this->faker->date(max: (new DateTime())->sub($dateInterval)),
            'gender' => Gender::getRandomCase(),
            'phone_number' => $this->faker->phoneNumber,
            'residence_id' => 1
        ];
    }
}
