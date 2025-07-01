<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'penjualans';

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barang_id',
        'jumlah_penjualan',
        'harga_saat_transaksi',
        'total_harga',
        'tgl_jual',
    ];

    /**
     * Tipe data yang harus di-cast ke tipe lain.
     * Ini membuat 'tgl_jual' menjadi objek Carbon yang powerful.
     *
     * @var array
     */
    protected $casts = [
        'tgl_jual' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi "many-to-one" dengan model Barang.
     * Setiap penjualan "milik satu" barang.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}