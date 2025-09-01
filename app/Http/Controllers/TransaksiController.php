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
     * Menampilkan halaman kasir untuk membuat transaksi baru.
     */
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('transaksi.create', compact('barangs'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_saat_transaksi' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'uang_bayar' => 'required|numeric|gte:total_harga',
        ]);

        $transaksi = null;
        try {
            DB::transaction(function () use ($request, &$transaksi) {
                $transaksi = Transaksi::create([
                    'user_id' => Auth::id(),
                    'nomor_transaksi' => 'TRX-' . time() . '-' . Str::upper(Str::random(4)),
                    'total_harga' => $request->total_harga,
                    'uang_bayar' => $request->uang_bayar,
                    'uang_kembali' => $request->uang_bayar - $request->total_harga,
                ]);

                foreach ($request->items as $item) {
                    $barang = Barang::find($item['barang_id']);
                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception('Stok untuk barang ' . $barang->nama_barang . ' tidak mencukupi.');
                    }
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $item['barang_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_saat_transaksi'],
                        'subtotal' => $item['jumlah'] * $item['harga_saat_transaksi'],
                    ]);
                    $barang->decrement('stok', $item['jumlah']);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('transaksi.show', $transaksi)->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Menampilkan halaman riwayat transaksi.
     */
    public function index()
    {
        $transaksis = Transaksi::with('user')->latest()->paginate(15);
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan detail satu transaksi (nota).
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Menampilkan form untuk mengedit transaksi (Hanya Admin).
     */
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('transaksi.edit', compact('transaksi', 'barangs'));
    }

    /**
     * Memperbarui transaksi (Hanya Admin).
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            // validasi lainnya...
        ]);

        try {
            DB::transaction(function () use ($request, $transaksi) {
                // 1. Kembalikan stok lama
                foreach ($transaksi->details as $detail) {
                    Barang::find($detail->barang_id)->increment('stok', $detail->jumlah);
                }
                // 2. Hapus detail lama
                $transaksi->details()->delete();
                // 3. Update data utama
                $transaksi->update([
                    'total_harga' => $request->total_harga,
                    'uang_bayar' => $request->uang_bayar,
                    'uang_kembali' => $request->uang_bayar - $request->total_harga,
                ]);
                // 4. Buat detail baru & kurangi stok baru
                foreach ($request->items as $item) {
                    $barang = Barang::find($item['barang_id']);
                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception('Stok untuk barang ' . $barang->nama_barang . ' tidak mencukupi.');
                    }
                    DetailTransaksi::create(['transaksi_id' => $transaksi->id, /* ...data lainnya... */]);
                    $barang->decrement('stok', $item['jumlah']);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Menghapus transaksi (Hanya Admin).
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::transaction(function () use ($transaksi) {
                // 1. Kembalikan stok barang
                foreach ($transaksi->details as $detail) {
                    Barang::find($detail->barang_id)->increment('stok', $detail->jumlah);
                }
                // 2. Hapus transaksi
                $transaksi->delete();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
    }
}
