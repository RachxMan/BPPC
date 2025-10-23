<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CaringTelepon;
use App\Models\User;

class CaringTeleponFactory extends Factory
{
    protected $model = CaringTelepon::class;

    public function definition()
    {
        return [
            'snd' => $this->faker->unique()->numerify('SND-###'),
            'user_id' => User::factory(),
            'witel' => $this->faker->word(),
            'type' => $this->faker->randomElement(['Type A', 'Type B']),
            'produk_bundling' => $this->faker->word(),
            'fi_home' => $this->faker->word(),
            'account_num' => $this->faker->numerify('ACC####'),
            'snd_group' => $this->faker->word(),
            'nama' => $this->faker->name(),
            'cp' => $this->faker->phoneNumber(),
            'datel' => $this->faker->word(),
            'payment_date' => $this->faker->date(),
            'status_bayar' => $this->faker->randomElement(['Lunas', 'Belum Lunas']),
            'no_hp' => $this->faker->phoneNumber(),
            'nama_real' => $this->faker->name(),
            'segmen_real' => $this->faker->word(),
            'status_call' => null,
            'keterangan' => null,
        ];
    }
}
