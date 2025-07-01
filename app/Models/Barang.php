<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'nama_barang',
        'harga',
        'stok',
        'foto_barang',
    ];

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}