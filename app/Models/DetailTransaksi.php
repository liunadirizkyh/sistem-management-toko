<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = ['transaksi_id', 'barang_id', 'jumlah', 'harga_satuan', 'subtotal'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class)->withTrashed();
    }
}
