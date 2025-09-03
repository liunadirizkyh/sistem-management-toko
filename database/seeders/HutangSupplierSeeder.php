<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HutangSupplier;
use Carbon\Carbon;

class HutangSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            'PT. Baja Perkasa',
            'CV. Semen Jaya',
            'TB. Sinar Abadi',
            'UD. Kayu Makmur',
            'Toko Cat Pelangi'
        ];
        $barangs = [
            'Besi Beton 10mm x25 btg, Paku 5-7cm x5 kg',
            'Semen Tiga Roda x20 sak',
            'Cat Tembok Avitex 5kg Putih x10 pail',
            'Pipa Paralon 1/2" x 50 btg, Lem Pipa x12 pcs',
            'Kayu Kaso 4/6 x 20 btg',
        ];

        for ($i = 1; $i <= 11; $i++) {
            $tanggalDatang = Carbon::now()->subDays(rand(5, 45));
            $hargaTotal = rand(5, 50) * 100000;
            $statusSeed = rand(1, 10); // Angka acak untuk menentukan status

            $jumlahDibayar = 0;
            $tanggalBayar = null;

            if ($statusSeed <= 3) { // 30% kemungkinan Lunas
                $jumlahDibayar = $hargaTotal;
                $tanggalBayar = $tanggalDatang->copy()->addDays(rand(1, 15));
            } elseif ($statusSeed <= 7) { // 40% kemungkinan Nyicil
                $jumlahDibayar = rand(1, $hargaTotal - 100000);
                $tanggalBayar = $tanggalDatang->copy()->addDays(rand(1, 20));
            }
            // Sisanya (30%) Belum Dibayar (jumlahDibayar = 0)

            HutangSupplier::create([
                'tanggal_datang' => $tanggalDatang,
                'nama_supplier' => $suppliers[array_rand($suppliers)],
                'kode_nota' => 'NOTA-' . time() . $i,
                'nama_barang' => $barangs[array_rand($barangs)],
                'harga_total' => $hargaTotal,
                'tanggal_jatuh_tempo' => $tanggalDatang->copy()->addDays(30),
                'jumlah_dibayar' => $jumlahDibayar,
                'tanggal_bayar' => $tanggalBayar,
            ]);
        }
    }
}
