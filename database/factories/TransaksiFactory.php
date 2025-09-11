<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransaksiFactory extends Factory
{
    public function definition(): array
    {
        $metode = $this->faker->randomElement(['cash', 'transfer']);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'nama_pelanggan' => $this->faker->optional(0.7)->name(),
            'metode_pembayaran' => $metode,
            'via_bank' => $metode == 'transfer' ? $this->faker->randomElement(['BCA', 'Mandiri', 'BRI']) : null,
            'nomor_transaksi' => 'TRX-' . Str::upper($this->faker->unique()->numerify('##########')),
            'total_harga' => 0,
            'uang_bayar' => 0,
            'uang_kembali' => 0,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Transaksi $transaksi) {
            if (Barang::where('stok', '>', 0)->count() == 0) return;

            $details = DetailTransaksi::factory()->count(rand(1, 3))->create([
                'transaksi_id' => $transaksi->id,
            ]);

            $totalHarga = $details->sum('subtotal');
            $uangBayar = $totalHarga;
            if ($transaksi->metode_pembayaran == 'cash') {
                $uangBayar += $this->faker->randomElement([0, 5000, 10000, 20000]);
            }

            $transaksi->total_harga = $totalHarga;
            $transaksi->uang_bayar = $uangBayar;
            $transaksi->uang_kembali = $uangBayar - $totalHarga;
            $transaksi->save();

            foreach ($details as $detail) {
                if ($detail->barang_id) {
                    Barang::find($detail->barang_id)->decrement('stok', $detail->jumlah);
                }
            }
        });
    }
}
