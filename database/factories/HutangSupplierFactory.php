<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class HutangSupplierFactory extends Factory
{
    public function definition(): array
    {
        $tanggalDatang = Carbon::instance($this->faker->dateTimeBetween('-1 year', 'now'));
        $hargaTotal = $this->faker->numberBetween(5, 100) * 100000;
        $jumlahDibayar = 0;
        $tanggalBayar = null;
        $statusSeed = rand(1, 10);

        if ($statusSeed <= 4) { // Lunas
            $jumlahDibayar = $hargaTotal;
            $tanggalBayar = $tanggalDatang->copy()->addDays(rand(5, 25));
        } elseif ($statusSeed <= 8) { // Nyicil
            $jumlahDibayar = $this->faker->numberBetween(100000, $hargaTotal - 100000);
            $tanggalBayar = $tanggalDatang->copy()->addDays(rand(5, 25));
        }

        return [
            'tanggal_datang' => $tanggalDatang,
            'nama_supplier' => $this->faker->company(),
            'kode_nota' => 'NOTA-' . $this->faker->unique()->numerify('######'),
            'nama_barang' => 'Pengadaan ' . $this->faker->randomElement(['Semen', 'Besi', 'Cat']),
            'harga_total' => $hargaTotal,
            'tanggal_jatuh_tempo' => $tanggalDatang->copy()->addDays(30),
            'jumlah_dibayar' => $jumlahDibayar,
            'tanggal_bayar' => $tanggalBayar,
        ];
    }
}
