<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HutangSupplierController;
use App\Http\Controllers\KodeBarangController;
use App\Http\Controllers\PiutangController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Route::get('/make-admin', function () {
//     // Bikin user baru
//     $user = User::create([
//         'name' => 'Super Admin',
//         'email' => 'admin@example.com',
//         'password' => Hash::make('password123'),
//     ]);

//     // Tambahkan role admin (kalau pakai Spatie Permission)
//     $user->assignRole('admin');

//     return "User admin berhasil dibuat dengan email: {$user->email} dan password: password123";
// });

// Rute untuk halaman utama, langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Semua rute yang memerlukan login dikelompokkan di sini
Route::middleware(['auth'])->group(function () {

    // --- Rute Umum (Semua Role Login) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute untuk Kasir & Admin ---
    Route::middleware('role:admin|kasir')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Rute Transaksi (Lihat & Buat)
        Route::get('/transaksi/baru', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');

        // Rute Barang (Hanya Lihat)
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');

        Route::get('/barang/search', [BarangController::class, 'search'])->name('barang.search');
    });

    // --- Rute Khusus Admin ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('kode-barang', KodeBarangController::class);
        Route::resource('hutang-supplier', HutangSupplierController::class);

        // Aksi Buat, Update & Hapus Barang
        Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
        Route::get('/barang/print', [BarangController::class, 'print'])->name('barang.print');

        // Aksi Update & Hapus Transaksi
        Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

        Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
        Route::get('/piutang/create', [PiutangController::class, 'create'])->name('piutang.create');
        Route::post('/piutang', [PiutangController::class, 'store'])->name('piutang.store');
        Route::get('/piutang/{pelanggan}', [PiutangController::class, 'show'])->name('piutang.show');
    });
});

require __DIR__ . '/auth.php';
