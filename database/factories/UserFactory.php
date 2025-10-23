<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName(),               // optional, sesuai tabel
            'nama_lengkap' => $this->faker->name(),               // ganti dari 'name'
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'no_telp' => $this->faker->phoneNumber(),            // kolom no_telp sesuai tabel
            'password' => Hash::make('password'),                // hashed default password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['admin', 'ca']), // random role (admin atau CA)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State untuk user admin
     */
    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    /**
     * State untuk user collection agent
     */
    public function collectionAgent()
    {
        return $this->state([
            'role' => 'ca',
        ]);
    }
}
