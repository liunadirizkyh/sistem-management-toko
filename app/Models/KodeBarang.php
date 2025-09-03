<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KodeBarang extends Model
{
    use HasFactory;
    protected $fillable = ['kode', 'harga_modal'];

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
