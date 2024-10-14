<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\TaggedUser;
use App\Models\Residence;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tag::factory(15)->create();

        // Generate the admin user
        User::factory()->create([
            'firstname' => 'test',
            'email' => 'test@example.com',
        ]);
        // Generate 10 residences
        Residence::factory(10)->create();

        // Generate a few normal users
        for($i = 0; $i < 10; $i++) {
            $user = User::factory()->create([
                'role' => 'user',
                'firstname' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
            ]);
            UserProfile::factory()->create([
                'user_id' => $user->id,
                'residence_id' => Residence::all()->random()->first()->id
            ]);
        }
    }
}
