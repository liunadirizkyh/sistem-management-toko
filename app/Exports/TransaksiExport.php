<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class TransaksiExport implements FromQuery, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $fromDate;
    protected $toDate;
    protected $search;

    public function __construct($fromDate, $toDate, $search)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->search = $search;
    }

    /**
     * Mengambil data berdasarkan filter tanpa menyertakan relasi user (kasir)
     */
    public function query()
    {
        $query = Transaksi::query();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->fromDate)->startOfDay(),
                Carbon::parse($this->toDate)->endOfDay()
            ]);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nomor_transaksi', 'like', "%{$this->search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$this->search}%");
            });
        }

        return $query->latest();
    }

    /**
     * Judul kolom Excel (Kolom Kasir dihapus)
     */
    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal',
            'Nama Pelanggan',
            'Total Belanja (Rp)',
            'Metode',
            'Bank/Keterangan',
        ];
    }

    /**
     * Pemetaan data (Kolom Kasir dihapus)
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->nomor_transaksi,
            $transaksi->created_at->format('d/m/Y H:i'),
            $transaksi->nama_pelanggan ?? 'Umum',
            (float) $transaksi->total_harga, // Pastikan tipe data numeric agar format Excel bekerja
            strtoupper($transaksi->metode_pembayaran),
            $transaksi->via_bank ?? '-',
        ];
    }

    /**
     * Format kolom (Menambahkan pemisah ribuan/titik pada kolom D)
     */
    public function columnFormats(): array
    {
        return [
            'D' => '#,##0', // Format Excel untuk pemisah ribuan
        ];
    }

    /**
     * Styling Header
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
