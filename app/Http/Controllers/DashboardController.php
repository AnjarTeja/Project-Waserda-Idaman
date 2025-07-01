<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metrik: Penjualan Hari Ini
        $penjualanHariIni = Penjualan::whereDate('tgl_jual', today())->sum('total_harga');

        // 2. Metrik: Jumlah Transaksi Hari Ini (sebelumnya 'jumlahPesanan')
        $jumlahTransaksi = Penjualan::whereDate('tgl_jual', today())->count();

        // 3. Metrik: Barang Tersedia (sebelumnya 'menuTersedia')
        $barangTersedia = Barang::where('stok', '>', 0)->count();

        // 4. Tabel: Penjualan Terbaru (sebelumnya 'pesananTerbaru')
        $penjualanTerbaru = Penjualan::with('barang')->latest('tgl_jual')->take(5)->get();

        // 5. Grafik: Data penjualan 7 hari terakhir
        $penjualanMingguan = Penjualan::select(
                DB::raw('DATE(tgl_jual) as tanggal'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('tgl_jual', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        $labelGrafik = [];
        $dataGrafik = [];
        $tanggalRange = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        foreach ($tanggalRange as $tanggal) {
            $labelGrafik[] = Carbon::parse($tanggal)->format('D');
            $penjualanPadaHariItu = $penjualanMingguan->firstWhere('tanggal', $tanggal);
            $dataGrafik[] = $penjualanPadaHariItu ? $penjualanPadaHariItu->total : 0;
        }

        // Kirim semua data ke view dengan nama variabel yang sudah diperbarui
        return view('dashboard', compact(
            'penjualanHariIni',
            'jumlahTransaksi',    // Diperbarui
            'barangTersedia',     // Diperbarui
            'penjualanTerbaru',   // Diperbarui
            'labelGrafik',
            'dataGrafik'
        ));
    }
}