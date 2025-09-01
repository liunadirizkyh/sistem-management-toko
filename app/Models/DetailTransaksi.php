<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $fillable = ['transaksi_id', 'barang_id', 'jumlah', 'harga_satuan', 'subtotal'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
