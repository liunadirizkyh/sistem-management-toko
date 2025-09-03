<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_datang',
        'nama_supplier',
        'kode_nota',
        'nama_barang',
        'harga_total',
        'tanggal_jatuh_tempo',
        'jumlah_dibayar',
        'tanggal_bayar',
    ];

    /**
     * Accessor untuk menentukan status hutang secara otomatis.
     */
    public function getStatusAttribute(): string
    {
        if ($this->jumlah_dibayar <= 0) {
            return 'Belum';
        }

        if ($this->jumlah_dibayar >= $this->harga_total) {
            return 'Lunas';
        }

        return 'Nyicil';
    }

    /**
     * Accessor untuk menentukan warna badge status.
     */
    public function getStatusColorAttribute(): string
    {
        switch ($this->status) {
            case 'Lunas':
                return 'bg-green-100 text-green-800';
            case 'Nyicil':
                return 'bg-gray-200';
            case 'Belum':
            default:
                return 'bg-red-100 text-red-800';
        }
    }
}
