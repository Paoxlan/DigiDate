<?php

namespace Database\Factories;

use App\Models\Residence;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidenceFactory extends Factory
{
    protected $model = Residence::class;

    public function definition(): array
    {
        return [
            'residence' => $this->faker->city,
        ];
    }
}
