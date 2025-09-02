<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Tetapkan tanggal default ke hari ini jika tidak ada input
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $day = $request->input('day', now()->day);

        // Tipe filter default ke 'harian'
        $filterType = $request->input('filter_type', 'daily');

        // Buat objek tanggal dari input untuk query
        $date = Carbon::create($year, $month, $day);

        $query = Transaksi::query();

        // Terapkan filter berdasarkan tipe yang dipilih
        switch ($filterType) {
            case 'monthly':
                // Filter untuk bulan dan tahun yang dipilih
                $query->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
                break;
            case 'yearly':
                // Filter untuk tahun yang dipilih
                $query->whereYear('created_at', $date->year);
                break;
            case 'daily':
            default:
                // Filter untuk tanggal yang dipilih
                $query->whereDate('created_at', $date);
                break;
        }

        // Hitung metrik berdasarkan query yang sudah difilter
        $pendapatan = $query->sum('total_harga');
        $jumlahTransaksi = $query->count();
        $transaksiIds = $query->pluck('id');
        $barangTerjual = DetailTransaksi::whereIn('transaksi_id', $transaksiIds)->sum('jumlah');

        // Kirim semua data ke view
        return view('dashboard', [
            'pendapatan' => $pendapatan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'barangTerjual' => $barangTerjual,
            // Kirim kembali input filter untuk ditampilkan di form
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedDay' => $day,
            'selectedFilterType' => $filterType,
        ]);
    }
}
