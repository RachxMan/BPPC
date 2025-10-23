<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),           // optional, sesuai tabelmu
            'nama_lengkap' => $this->faker->name(),           // ganti dari 'name'
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'no_telp' => $this->faker->phoneNumber(),        // kolom no_telp sesuai tabel
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'CA', // default dummy user
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
