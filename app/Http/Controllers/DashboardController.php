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

        // Buat satu query dasar untuk penjualan yang sudah difilter
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

        // Hitung metrik penjualan
        $pendapatanCash = (clone $queryPenjualan)->where('metode_pembayaran', 'cash')->sum('total_harga');
        $pendapatanTransfer = (clone $queryPenjualan)->where('metode_pembayaran', 'transfer')->sum('total_harga');
        $pendapatanTotal = $pendapatanCash + $pendapatanTransfer;
        $jumlahTransaksi = (clone $queryPenjualan)->count();
        $transaksiIds = (clone $queryPenjualan)->pluck('id');
        $barangTerjual = DetailTransaksi::whereIn('transaksi_id', $transaksiIds)->sum('jumlah');

        // PERBAIKAN: Hitung Total Hutang yang TERCATAT pada periode filter
        $queryHutang = HutangSupplier::query();
        switch ($filterType) {
            case 'monthly':
                $queryHutang->whereYear('tanggal_datang', $date->year)->whereMonth('tanggal_datang', $date->month);
                break;
            case 'yearly':
                $queryHutang->whereYear('tanggal_datang', $date->year);
                break;
            default: // daily
                $queryHutang->whereDate('tanggal_datang', $date);
                break;
        }

        // Dari hutang yang masuk di periode tsb, hitung berapa total yang belum lunas.
        $totalHutang = (clone $queryHutang)
            ->whereColumn('jumlah_dibayar', '<', 'harga_total')
            ->sum(DB::raw('harga_total - jumlah_dibayar'));


        // Ambil Top 3 Pelanggan (logika ini sudah benar karena menggunakan query penjualan yang terfilter)
        $topPelanggan = (clone $queryPenjualan)
            ->whereNotNull('nama_pelanggan')
            ->where('nama_pelanggan', '!=', '')
            ->select('nama_pelanggan', DB::raw('SUM(total_harga) as total_pembelian'))
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_pembelian')
            ->limit(2)
            ->get();

        // Kirim semua data ke view
        return view('dashboard', [
            'pendapatanCash' => $pendapatanCash,
            'pendapatanTransfer' => $pendapatanTransfer,
            'pendapatanTotal' => $pendapatanTotal,
            'jumlahTransaksi' => $jumlahTransaksi,
            'barangTerjual' => $barangTerjual,
            'totalHutang' => $totalHutang,
            'topPelanggan' => $topPelanggan,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedDay' => $day,
            'selectedFilterType' => $filterType,
        ]);
    }
}
