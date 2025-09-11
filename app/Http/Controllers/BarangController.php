<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KodeBarang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BarangController extends Controller
{
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

    public function create()
    {
        $kodeBarangs = KodeBarang::orderBy('kode')->get();
        return view('barang.create', compact('kodeBarangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang_id' => 'required|exists:kode_barangs,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        $kodeBarangs = KodeBarang::orderBy('kode')->get();
        return view('barang.edit', compact('barang', 'kodeBarangs'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang_id' => 'required|exists:kode_barangs,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->details()->exists()) {
            return redirect()->route('barang.index')
                ->withErrors(['error' => 'Gagal! Barang "' . $barang->nama_barang . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi.']);
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->input('term', '');

        $barangs = Barang::with('kodeBarang')
            ->where(function ($query) use ($term) {
                $query->where('nama_barang', 'like', "%{$term}%")
                    ->orWhereHas('kodeBarang', function ($subQuery) use ($term) {
                        $subQuery->where('kode', 'like', "%{$term}%");
                    });
            })
            ->where('stok', '>', 0)
            ->limit(20)
            ->get();

        $results = $barangs->map(function ($barang) {
            return [
                'id' => $barang->id,
                'text' => "{$barang->nama_barang} (Stok: {$barang->stok})",
                'data' => [
                    'nama' => $barang->nama_barang,
                    'harga' => $barang->harga_jual,
                    'stok' => $barang->stok,
                ]
            ];
        });

        return response()->json($results);
    }
}
