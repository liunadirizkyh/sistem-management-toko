<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailTransaksiFactory extends Factory
{
    public function definition(): array
    {
        $barang = Barang::where('stok', '>', 5)->inRandomOrder()->first();
        if (!$barang) return [];

        $jumlah = $this->faker->numberBetween(1, 5);

        return [
            'barang_id' => $barang->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $barang->harga_jual,
            'subtotal' => $barang->harga_jual * $jumlah,
        ];
    }
}
