<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_barang_id',
        'nama_barang',
        'satuan',
        'harga_jual',
        'stok',
    ];

    public function kodeBarang(): BelongsTo
    {
        return $this->belongsTo(KodeBarang::class);
    }
}
