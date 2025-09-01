<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat User dengan Role Admin
        $admin = User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'email_verified_at' => now(), // Langsung verifikasi email
            'password' => Hash::make('password'), // Ganti 'password' dengan password yang aman
        ]);
        $admin->assignRole('admin');

        // Membuat User dengan Role Kasir
        $kasir = User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@toko.com',
            'email_verified_at' => now(), // Langsung verifikasi email
            'password' => Hash::make('password'), // Ganti 'password' dengan password yang aman
        ]);
        $kasir->assignRole('kasir');
    }
}
