<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HutangSupplier;

class HutangSupplierSeeder extends Seeder
{
    public function run(): void
    {
        HutangSupplier::factory()->count(5000)->create();
    }
}
