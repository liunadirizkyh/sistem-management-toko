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
}
