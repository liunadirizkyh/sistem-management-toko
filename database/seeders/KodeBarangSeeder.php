<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KodeBarang;

class KodeBarangSeeder extends Seeder
{
    public function run(): void
    {
        KodeBarang::create(['kode' => 'SMN-GRS', 'harga_modal' => 68000]);
        KodeBarang::create(['kode' => 'SMN-PDG', 'harga_modal' => 65000]);
        KodeBarang::create(['kode' => 'BSI-8', 'harga_modal' => 45000]);
        KodeBarang::create(['kode' => 'BSI-10', 'harga_modal' => 62000]);
        KodeBarang::create(['kode' => 'BSI-12', 'harga_modal' => 89000]);
        KodeBarang::create(['kode' => 'BTA-MRH', 'harga_modal' => 800]);
        KodeBarang::create(['kode' => 'PSR-KSR', 'harga_modal' => 250000]);
        KodeBarang::create(['kode' => 'SPL-KCL', 'harga_modal' => 120000]);
        KodeBarang::create(['kode' => 'CAT-W-5', 'harga_modal' => 110000]);
        KodeBarang::create(['kode' => 'CAT-D-5', 'harga_modal' => 95000]);
        KodeBarang::create(['kode' => 'PPA-0.5', 'harga_modal' => 22000]);
    }
}
