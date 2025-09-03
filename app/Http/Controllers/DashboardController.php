<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\HutangSupplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Tetapkan tanggal default dan tipe filter
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $day = $request->input('day', now()->day);
        $filterType = $request->input('filter_type', 'daily');
        $date = Carbon::create($year, $month, $day);

        // --- Query Penjualan (Berdasarkan tanggal transaksi) ---
        $queryPenjualan = Transaksi::query();
        switch ($filterType) {
            case 'monthly':
                $queryPenjualan->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
                break;
            case 'yearly':
                $queryPenjualan->whereYear('created_at', $date->year);
                break;
            default:
                $queryPenjualan->whereDate('created_at', $date);
                break;
        }
        $pendapatan = $queryPenjualan->sum('total_harga');
        $jumlahTransaksi = $queryPenjualan->count();
        $transaksiIds = $queryPenjualan->pluck('id');
        $barangTerjual = DetailTransaksi::whereIn('transaksi_id', $transaksiIds)->sum('jumlah');

        // --- PERBAIKAN UTAMA: Semua query hutang sekarang berdasarkan 'tanggal_datang' ---

        // Buat satu query dasar untuk hutang yang difilter berdasarkan 'tanggal_datang'
        $queryHutang = HutangSupplier::query();
        switch ($filterType) {
            case 'monthly':
                $queryHutang->whereYear('tanggal_datang', $date->year)->whereMonth('tanggal_datang', $date->month);
                break;
            case 'yearly':
                $queryHutang->whereYear('tanggal_datang', $date->year);
                break;
            default:
                $queryHutang->whereDate('tanggal_datang', $date);
                break;
        }

        // Hitung total hutang yang TERCATAT pada periode ini dan BELUM LUNAS
        $totalHutang = (clone $queryHutang)
            ->whereColumn('jumlah_dibayar', '<', 'harga_total')
            ->sum(DB::raw('harga_total - jumlah_dibayar'));

        // Hitung sisa hutang NYICIL yang TERCATAT pada periode ini
        $sisaHutangNyicil = (clone $queryHutang)
            ->where('jumlah_dibayar', '>', 0)
            ->whereColumn('jumlah_dibayar', '<', 'harga_total')
            ->sum(DB::raw('harga_total - jumlah_dibayar'));

        // Hitung jumlah yang sudah dibayarkan dari hutang yang TERCATAT pada periode ini
        $hutangDilunasiPeriodeIni = (clone $queryHutang)->sum('jumlah_dibayar');


        return view('dashboard', [
            'pendapatan' => $pendapatan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'barangTerjual' => $barangTerjual,
            'totalHutang' => $totalHutang,
            'sisaHutangNyicil' => $sisaHutangNyicil,
            'hutangDilunasiPeriodeIni' => $hutangDilunasiPeriodeIni,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedDay' => $day,
            'selectedFilterType' => $filterType,
        ]);
    }
}
