<?php

namespace Database\Factories;

use App\Models\KodeBarang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        $kodeBarang = KodeBarang::inRandomOrder()->first();
        $namaProduk = ['Semen', 'Besi Beton', 'Cat Tembok', 'Pipa PVC', 'Pasir', 'Bata Ringan', 'Keramik', 'Genteng'];
        $ukuran = ['10mm', '5kg', '50kg', '4"', '8mm', '1/2 inch', '40x40', 'Super'];

        return [
            'kode_barang_id' => $kodeBarang->id,
            'nama_barang' => $this->faker->randomElement($namaProduk) . ' ' . $this->faker->randomElement($ukuran),
            'satuan' => $this->faker->randomElement(['sak', 'batang', 'pail', 'm3', 'pcs', 'lembar']),
            'harga_jual' => $kodeBarang->harga_modal + $this->faker->numberBetween(5000, 50000),
            'stok' => $this->faker->numberBetween(10, 500),
        ];
    }
}
