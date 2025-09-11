<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KodeBarang;

class KodeBarangSeeder extends Seeder
{
    public function run(): void
    {
        KodeBarang::factory()->count(5000)->create();
    }
}
