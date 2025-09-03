<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\KodeBarang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            ['nama' => 'Semen Gresik 50kg', 'kode' => 'SMN-GRS', 'satuan' => 'sak', 'jual' => 75000, 'stok' => 100],
            ['nama' => 'Semen Padang 50kg', 'kode' => 'SMN-PDG', 'satuan' => 'sak', 'jual' => 72000, 'stok' => 120],
            ['nama' => 'Besi Beton 8mm', 'kode' => 'BSI-8', 'satuan' => 'batang', 'jual' => 52000, 'stok' => 200],
            ['nama' => 'Besi Beton 10mm', 'kode' => 'BSI-10', 'satuan' => 'batang', 'jual' => 70000, 'stok' => 150],
            ['nama' => 'Besi Beton 12mm', 'kode' => 'BSI-12', 'satuan' => 'batang', 'jual' => 98000, 'stok' => 80],
            ['nama' => 'Bata Merah Press', 'kode' => 'BTA-MRH', 'satuan' => 'pcs', 'jual' => 1000, 'stok' => 5000],
            ['nama' => 'Pasir Kasar', 'kode' => 'PSR-KSR', 'satuan' => 'm3', 'jual' => 280000, 'stok' => 10],
            ['nama' => 'Split Kecil', 'kode' => 'SPL-KCL', 'satuan' => 'm3', 'jual' => 150000, 'stok' => 15],
            ['nama' => 'Cat Tembok Putih 5kg', 'kode' => 'CAT-W-5', 'satuan' => 'pail', 'jual' => 125000, 'stok' => 50],
            ['nama' => 'Cat Dasar 5kg', 'kode' => 'CAT-D-5', 'satuan' => 'pail', 'jual' => 105000, 'stok' => 40],
            ['nama' => 'Pipa Paralon 1/2 inch', 'kode' => 'PPA-0.5', 'satuan' => 'batang', 'jual' => 28000, 'stok' => 250],
        ];

        foreach ($barangs as $barang) {
            $kodeBarang = KodeBarang::where('kode', $barang['kode'])->first();
            if ($kodeBarang) {
                Barang::create([
                    'kode_barang_id' => $kodeBarang->id,
                    'nama_barang' => $barang['nama'],
                    'satuan' => $barang['satuan'],
                    'harga_jual' => $barang['jual'],
                    'stok' => $barang['stok'],
                ]);
            }
        }
    }
}
