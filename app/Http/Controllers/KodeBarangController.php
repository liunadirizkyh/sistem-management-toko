<?php

namespace App\Http\Controllers;

use App\Models\KodeBarang;
use Illuminate\Http\Request;

class KodeBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = KodeBarang::latest()
            ->orderBy('kode', 'asc');

        if ($search) {
            $query->where('kode', 'like', "%{$search}%");
        }

        $kodeBarangs = $query->paginate($perPage)->appends($request->query());

        return view('kode-barang.index', [
            'kodeBarangs' => $kodeBarangs,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kode-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kode_barangs,kode',
            'harga_modal' => 'required|numeric|min:0',
        ]);

        KodeBarang::create($request->all());

        return redirect()->route('kode-barang.index')
            ->with('success', 'Kode Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * (Tidak digunakan dalam alur ini, karena kita langsung ke edit)
     */
    public function show(KodeBarang $kodeBarang)
    {
        return redirect()->route('kode-barang.edit', $kodeBarang);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KodeBarang $kodeBarang)
    {
        return view('kode-barang.edit', compact('kodeBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KodeBarang $kodeBarang)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kode_barangs,kode,' . $kodeBarang->id,
            'harga_modal' => 'required|numeric|min:0',
        ]);

        $kodeBarang->update($request->all());

        return redirect()->route('kode-barang.index')
            ->with('success', 'Kode Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KodeBarang $kodeBarang)
    {
        // Proteksi: Cek apakah kode barang ini sudah terpakai di tabel 'barangs'
        if ($kodeBarang->barangs()->exists()) {
            return redirect()->route('kode-barang.index')
                ->withErrors(['error' => 'Gagal! Kode "' . $kodeBarang->kode . '" tidak dapat dihapus karena sudah digunakan oleh barang lain.']);
        }

        $kodeBarang->delete();

        return redirect()->route('kode-barang.index')
            ->with('success', 'Kode Barang berhasil dihapus!');
    }
}
