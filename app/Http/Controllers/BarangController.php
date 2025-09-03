<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KodeBarang; // Pastikan ini di-import
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Sertakan relasi 'kodeBarang' untuk efisiensi query
        $query = Barang::with('kodeBarang')->latest();

        if ($search) {
            // Cari berdasarkan nama barang ATAU kode dari tabel relasi
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhereHas('kodeBarang', function ($subQuery) use ($search) {
                        $subQuery->where('kode', 'like', "%{$search}%");
                    });
            });
        }

        $barangs = $query->paginate($perPage)->appends($request->query());

        return view('barang.index', [
            'barangs' => $barangs,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Menampilkan form untuk menambah barang baru.
     */
    public function create()
    {
        // Kirim daftar KodeBarang ke view
        $kodeBarangs = KodeBarang::orderBy('kode')->get();
        return view('barang.create', compact('kodeBarangs'));
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan, harga_beli dihilangkan
        $request->validate([
            'kode_barang_id' => 'required|exists:kode_barangs,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        // Langsung simpan data dari form, karena harga_beli tidak lagi disimpan
        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit(Barang $barang)
    {
        // Kirim daftar KodeBarang ke view
        $kodeBarangs = KodeBarang::orderBy('kode')->get();
        return view('barang.edit', compact('barang', 'kodeBarangs'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        // Validasi disesuaikan, harga_beli dihilangkan
        $request->validate([
            'kode_barang_id' => 'required|exists:kode_barangs,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        // Langsung update data dari form
        $barang->update($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy(Barang $barang)
    {
        // Proteksi agar barang yang ada di transaksi tidak bisa dihapus
        if ($barang->details()->exists()) {
            return redirect()->route('barang.index')
                ->withErrors(['error' => 'Gagal! Barang "' . $barang->nama_barang . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi.']);
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
