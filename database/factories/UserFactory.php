<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // default password
            'role' => $this->faker->randomElement(['admin', 'ca']), // random role (admin or collection agent)
            'nama_lengkap' => $this->faker->name,
            'no_telp' => $this->faker->phoneNumber,
        ];
    }

    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    public function collectionAgent()
    {
        return $this->state([
            'role' => 'ca',
        ]);
    }
}

