<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 users using the factory
        User::factory(10)->create();

        // Create a specific user
        $user = User::create([
            'id' => 11,
            'name' => 'khurshid',
            'email' => 'khurshid@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Sync roles for the created user
        $user->roles()->sync([1, 2]);
    }
}
