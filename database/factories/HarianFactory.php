<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Harian;

class HarianFactory extends Factory
{
    protected $model = Harian::class;

    public function definition()
    {
        return [
            'witel' => $this->faker->city,
            'type' => $this->faker->word,
            'produk_bundling' => $this->faker->word,
            'fi_home' => $this->faker->word,
            'account_num' => $this->faker->unique()->numberBetween(100000, 999999),
            'snd' => $this->faker->unique()->numberBetween(10000, 99999),
            'snd_group' => $this->faker->word,
            'nama' => $this->faker->name,
            'cp' => $this->faker->phoneNumber,
            'datel' => $this->faker->word,
            'payment_date' => $this->faker->date(),
            'status_bayar' => $this->faker->randomElement(['Lunas','Belum Lunas']),
            'no_hp' => $this->faker->phoneNumber,
            'nama_real' => $this->faker->name,
            'segmen_real' => $this->faker->word,
        ];
    }
}
