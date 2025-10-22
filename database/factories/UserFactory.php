<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_telp' => $this->faker->numerify('08##########'),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // default password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['admin', 'ca']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
