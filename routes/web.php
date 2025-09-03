<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController; // Tambahkan ini jika Anda menggunakan DashboardController
use App\Http\Controllers\KodeBarangController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk halaman utama, langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Semua rute yang memerlukan login dikelompokkan di sini
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Rute Umum (Semua Role Login) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute untuk Kasir & Admin ---
    Route::middleware('role:admin|kasir')->group(function () {
        Route::get('/transaksi/baru', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // --- Rute Khusus Admin ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('barang', BarangController::class);
        Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
        Route::resource('kode-barang', KodeBarangController::class);
    });
});

require __DIR__ . '/auth.php';
