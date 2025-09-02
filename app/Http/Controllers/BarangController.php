<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input dari request, berikan nilai default
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 data per halaman

        // Mulai query dasar, urutkan dari yang terbaru
        $query = Barang::latest();

        // Terapkan filter pencarian jika ada input
        if ($search) {
            // Cari berdasarkan Nama Barang ATAU Kode Barang
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Lakukan paginasi setelah semua filter diterapkan
        // Gunakan appends() agar parameter filter tetap ada saat pindah halaman
        $barangs = $query->paginate($perPage)->appends($request->query());

        // Kirim data ke view
        return view('barang.index', [
            'barangs' => $barangs,
            'search' => $search,   // Kirim kembali term pencarian untuk ditampilkan di form
            'perPage' => $perPage, // Kirim kembali jumlah per halaman untuk dropdown
        ]);
    }

    /**
     * Menampilkan form untuk menambah barang baru.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'nullable|string|max:50|unique:barangs',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        // Buat record baru
        Barang::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu barang (opsional, bisa dilewati jika tidak perlu).
     */
    public function show(Barang $barang)
    {
        // Jika Anda butuh halaman detail, buat view 'barang.show'
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            // Pastikan kode barang unik, tapi abaikan untuk item saat ini
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        // Update record
        $barang->update($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
