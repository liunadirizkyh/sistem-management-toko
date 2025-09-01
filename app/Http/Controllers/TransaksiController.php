<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman kasir/form transaksi baru.
     */
    public function create()
    {
        // Ambil semua data barang untuk ditampilkan di pilihan
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('transaksi.create', compact('barangs'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_saat_transaksi' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'uang_bayar' => 'required|numeric|gte:total_harga', // Uang bayar harus >= total harga
        ]);

        try {
            // Gunakan DB Transaction untuk memastikan semua query berhasil
            DB::transaction(function () use ($request) {
                // 1. Buat record di tabel 'transaksis'
                $transaksi = Transaksi::create([
                    'user_id' => Auth::id(),
                    'nomor_transaksi' => 'TRX-' . time() . '-' . Str::upper(Str::random(4)),
                    'total_harga' => $request->total_harga,
                    'uang_bayar' => $request->uang_bayar,
                    'uang_kembali' => $request->uang_bayar - $request->total_harga,
                ]);

                // 2. Loop dan simpan setiap item ke 'detail_transaksis' & kurangi stok
                foreach ($request->items as $item) {
                    $barang = Barang::find($item['barang_id']);

                    // Cek ketersediaan stok
                    if ($barang->stok < $item['jumlah']) {
                        // Jika stok tidak cukup, batalkan transaksi
                        throw new \Exception('Stok untuk barang ' . $barang->nama_barang . ' tidak mencukupi.');
                    }

                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_saat_transaksi'],
                        'subtotal' => $item['jumlah'] * $item['harga_saat_transaksi'],
                    ]);

                    // 3. Kurangi stok barang
                    $barang->decrement('stok', $item['jumlah']);
                }
            });
        } catch (\Exception $e) {
            // Jika terjadi error (misal: stok habis), kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        // Jika berhasil, redirect ke halaman riwayat atau cetak nota dengan pesan sukses
        // (Untuk saat ini kita redirect ke halaman create lagi)
        return redirect()->route('transaksi.create')->with('success', 'Transaksi berhasil disimpan!');
    }

    // Anda bisa menambahkan method index() untuk riwayat dan show()/print() untuk nota nanti
}
