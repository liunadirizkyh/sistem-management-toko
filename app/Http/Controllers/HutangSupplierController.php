<?php

namespace App\Http\Controllers;

use App\Models\HutangSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan DB facade di-import

class HutangSupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = HutangSupplier::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_supplier', 'like', "%{$search}%")
                    ->orWhere('kode_nota', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%");
            });
        }

        $query->when($status, function ($q, $status) {
            if ($status == 'lunas') {
                return $q->whereColumn('jumlah_dibayar', '>=', 'harga_total');
            }
            if ($status == 'nyicil') {
                return $q->where('jumlah_dibayar', '>', 0)->whereColumn('jumlah_dibayar', '<', 'harga_total');
            }
            if ($status == 'belum_dibayar') {
                return $q->where('jumlah_dibayar', '<=', 0);
            }
        });

        $hutangs = $query->paginate($perPage)->appends($request->query());

        return view('hutang-supplier.index', [
            'hutangs' => $hutangs,
            'search' => $search,
            'perPage' => $perPage,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('hutang-supplier.create');
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Simpan hasil validasi ke dalam variabel $validated
        $validated = $request->validate([
            'tanggal_datang' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'kode_nota' => 'required|string|max:50|unique:hutang_suppliers,kode_nota',
            'nama_barang' => 'required|string',
            'harga_total' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
        ]);

        $validated['jumlah_dibayar'] = $validated['jumlah_dibayar'] ?? 0;

        HutangSupplier::create($validated);
        return redirect()->route('hutang-supplier.index')->with('success', 'Data hutang berhasil ditambahkan.');
    }

    public function edit(HutangSupplier $hutangSupplier)
    {
        return view('hutang-supplier.edit', compact('hutangSupplier'));
    }

    public function update(Request $request, HutangSupplier $hutangSupplier)
    {
        // PERBAIKAN: Simpan hasil validasi ke dalam variabel $validated
        $validated = $request->validate([
            'tanggal_datang' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'kode_nota' => 'required|string|max:50|unique:hutang_suppliers,kode_nota,' . $hutangSupplier->id,
            'nama_barang' => 'required|string',
            'harga_total' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
        ]);

        $validated['jumlah_dibayar'] = $validated['jumlah_dibayar'] ?? 0;

        $hutangSupplier->update($validated);
        return redirect()->route('hutang-supplier.index')->with('success', 'Data hutang berhasil diperbarui.');
    }

    public function destroy(HutangSupplier $hutangSupplier)
    {
        $hutangSupplier->delete();
        return redirect()->route('hutang-supplier.index')->with('success', 'Data hutang berhasil dihapus.');
    }
}
