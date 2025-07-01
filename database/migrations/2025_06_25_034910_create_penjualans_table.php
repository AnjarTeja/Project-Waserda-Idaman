<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('barang_id')->constrained('barangs');

            $table->integer('jumlah_penjualan');
            
            $table->integer('harga_saat_transaksi');

            $table->integer('total_harga');
            $table->timestamp('tgl_jual')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};