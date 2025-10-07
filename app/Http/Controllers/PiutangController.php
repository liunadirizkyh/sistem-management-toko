<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');

        $query = Pelanggan::orderBy('nama_pelanggan', 'asc');

        if ($search) {
            $query->where('nama_pelanggan', 'like', "%{$search}%");
        }

        if ($status == 'hutang') {
            $query->where('saldo', '>', 0);
        } elseif ($status == 'deposit') {
            $query->where('saldo', '<', 0);
        }

        $pelanggans = $query->paginate($perPage)->appends($request->query());

        return view('piutang.index', [
            'pelanggans' => $pelanggans,
            'search' => $search,
            'perPage' => $perPage,
            'status' => $status,
        ]);
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        return view('piutang.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id_existing' => 'required_without:pelanggan_id_new|nullable|exists:pelanggans,id',
            'pelanggan_id_new' => 'required_without:pelanggan_id_existing|nullable|string|max:255|unique:pelanggans,nama_pelanggan',
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pengambilan,pembayaran',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                if ($request->filled('pelanggan_id_new')) {
                    $pelanggan = Pelanggan::create(['nama_pelanggan' => $request->pelanggan_id_new]);
                } else {
                    $pelanggan = Pelanggan::find($request->pelanggan_id_existing);
                }

                Piutang::create([
                    'pelanggan_id' => $pelanggan->id,
                    'tanggal' => $request->tanggal,
                    'tipe' => $request->tipe,
                    'deskripsi' => $request->deskripsi,
                    'jumlah' => $request->jumlah,
                ]);

                if ($request->tipe == 'pengambilan') {
                    $pelanggan->increment('saldo', $request->jumlah);
                } else {
                    $pelanggan->decrement('saldo', $request->jumlah);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('piutang.index')->with('success', 'Transaksi piutang berhasil dicatat.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $piutangs = $pelanggan->piutangs()->paginate(20);
        return view('piutang.show', compact('pelanggan', 'piutangs'));
    }

    public function edit(Piutang $piutang)
    {
        $piutang->load('pelanggan');
        return view('piutang.edit', compact('piutang'));
    }

    public function update(Request $request, Piutang $piutang)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pengambilan,pembayaran',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $pelanggan = $piutang->pelanggan;

        try {
            DB::transaction(function () use ($validated, $piutang, $pelanggan) {
                if ($piutang->tipe == 'pengambilan') {
                    $pelanggan->decrement('saldo', $piutang->jumlah);
                } else {
                    $pelanggan->increment('saldo', $piutang->jumlah);
                }

                if ($validated['tipe'] == 'pengambilan') {
                    $pelanggan->increment('saldo', $validated['jumlah']);
                } else {
                    $pelanggan->decrement('saldo', $validated['jumlah']);
                }

                $piutang->update($validated);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('piutang.show', $pelanggan)->with('success', 'Transaksi piutang berhasil diperbarui.');
    }

    public function destroy(Piutang $piutang)
    {
        $pelanggan = $piutang->pelanggan;

        try {
            DB::transaction(function () use ($piutang, $pelanggan) {
                if ($piutang->tipe == 'pengambilan') {
                    $pelanggan->decrement('saldo', $piutang->jumlah);
                } else {
                    $pelanggan->increment('saldo', $piutang->jumlah);
                }

                $piutang->delete();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }

        return redirect()->route('piutang.show', $pelanggan)->with('success', 'Transaksi piutang berhasil dihapus.');
    }

    public function destroyPelanggan(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('piutang.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
