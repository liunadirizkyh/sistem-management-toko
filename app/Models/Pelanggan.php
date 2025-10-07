<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;
    protected $fillable = ['nama_pelanggan', 'saldo'];

    public function piutangs(): HasMany
    {
        return $this->hasMany(Piutang::class)->latest();
    }
}
