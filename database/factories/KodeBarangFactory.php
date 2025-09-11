<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KodeBarangFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode' => Str::upper($this->faker->unique()->bothify('???-####')),
            'harga_modal' => $this->faker->numberBetween(5000, 500000),
        ];
    }
}
