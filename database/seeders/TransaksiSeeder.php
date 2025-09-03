<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = Barang::all();
        $users = User::all();

        if ($barangs->isEmpty() || $users->isEmpty()) {
            $this->command->info('Tidak dapat membuat transaksi, data barang atau user kosong.');
            return;
        }

        for ($i = 0; $i < 11; $i++) {
            DB::transaction(function () use ($barangs, $users) {
                $itemsInCart = $barangs->random(rand(1, 3))->map(function ($barang) {
                    $jumlah = rand(1, 5);
                    // Pastikan jumlah tidak melebihi stok
                    if ($jumlah > $barang->stok) {
                        $jumlah = $barang->stok > 0 ? 1 : 0;
                    }
                    if ($jumlah == 0) return null;

                    return [
                        'barang' => $barang,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $barang->harga_jual,
                        'subtotal' => $barang->harga_jual * $jumlah,
                    ];
                })->whereNotNull();

                if ($itemsInCart->isEmpty()) return;

                $totalHarga = $itemsInCart->sum('subtotal');
                $uangBayar = $totalHarga + rand(0, 5) * 10000;

                $transaksi = Transaksi::create([
                    'user_id' => $users->random()->id,
                    'nomor_transaksi' => 'TRX-' . time() . '-' . Str::upper(Str::random(4)),
                    'total_harga' => $totalHarga,
                    'uang_bayar' => $uangBayar,
                    'uang_kembali' => $uangBayar - $totalHarga,
                    'created_at' => now()->subDays(rand(0, 30)), // Buat tanggal transaksi acak
                ]);

                foreach ($itemsInCart as $item) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $item['barang']->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                    ]);
                    $item['barang']->decrement('stok', $item['jumlah']);
                }
            });
        }
    }
}
