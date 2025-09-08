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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Transaksi::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_transaksi', 'like', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->paginate($perPage)->appends($request->query());

        return view('transaksi.index', [
            'transaksis' => $transaksis,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('transaksi.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'nullable|string|max:255',
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
                    'nama_pelanggan' => $request->nama_pelanggan,
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

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('transaksi.edit', compact('transaksi', 'barangs'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'nama_pelanggan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_saat_transaksi' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'uang_bayar' => 'required|numeric|gte:total_harga',
        ]);

        try {
            DB::transaction(function () use ($request, $transaksi) {
                // Kembalikan stok lama
                foreach ($transaksi->details as $detail) {
                    $barangLama = Barang::withTrashed()->find($detail->barang_id);
                    if ($barangLama) {
                        $barangLama->increment('stok', $detail->jumlah);
                    }
                }

                $transaksi->details()->delete();
                $transaksi->update([
                    'nama_pelanggan' => $request->nama_pelanggan,
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
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::transaction(function () use ($transaksi) {
                foreach ($transaksi->details as $detail) {
                    $barang = Barang::withTrashed()->find($detail->barang_id);
                    if ($barang) {
                        $barang->increment('stok', $detail->jumlah);
                    }
                }
                $transaksi->delete();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
    }
}
