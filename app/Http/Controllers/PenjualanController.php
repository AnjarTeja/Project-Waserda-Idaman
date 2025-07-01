<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
{
    $datesWithSales = Penjualan::select(DB::raw('DATE(tgl_jual) as tanggal'))
                            ->distinct()
                            ->pluck('tanggal');
    $tanggalPenjualan = $datesWithSales->map(function ($date) {
        return Carbon::parse($date)->format('Y-m-d');
    })->toArray();
    $query = Penjualan::query()->with('barang')->latest('tgl_jual');
    if ($request->filled('filter_tanggal')) {
        $query->whereDate('tgl_jual', $request->filter_tanggal);
    }
    if ($request->filled('filter_bulan')) {
        list($tahun, $bulan) = explode('-', $request->filter_bulan);
        $query->whereYear('tgl_jual', $tahun)->whereMonth('tgl_jual', $bulan);
    }
    if ($request->filled('filter_tahun')) {
        $query->whereYear('tgl_jual', $request->filter_tahun);
    }
    $totalOmset = $query->clone()->sum('total_harga');
    
    $penjualans = $query->paginate(15)->withQueryString();

    return view('penjualan.index', compact('penjualans', 'tanggalPenjualan', 'totalOmset'));
}
    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('penjualan.create', compact('barangs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_penjualan' => 'required|integer|min:1',
        ]);
        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->jumlah_penjualan) {
            return back()->withErrors(['jumlah_penjualan' => 'Stok tidak mencukupi. Stok tersisa: ' . $barang->stok])->withInput();
        }
        DB::transaction(function () use ($request, $barang) {
            Penjualan::create([
                'barang_id' => $barang->id,
                'jumlah_penjualan' => $request->jumlah_penjualan,
                'harga_saat_transaksi' => $barang->harga,
                'total_harga' => $barang->harga * $request->jumlah_penjualan,
                'tgl_jual' => now()
            ]);
            $barang->decrement('stok', $request->jumlah_penjualan);
        });
        return redirect()->route('penjualans.index')->with('success', 'Transaksi penjualan berhasil dicatat.');
    }

    public function edit(Penjualan $penjualan)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('penjualan.edit', compact('penjualan', 'barangs'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $validatedData = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_penjualan' => 'required|integer|min:1',
            'tgl_jual' => 'required|date',
        ]);
        $barangBaru = Barang::findOrFail($validatedData['barang_id']);
        $barangLama = $penjualan->barang;
        $jumlahLama = $penjualan->jumlah_penjualan;
        $jumlahBaru = $validatedData['jumlah_penjualan'];
        $stokTersedia = $barangBaru->stok;
        if ($barangLama && $barangLama->id === $barangBaru->id) {
            $stokTersedia += $jumlahLama;
        }
        if ($stokTersedia < $jumlahBaru) {
            return back()->withErrors(['jumlah_penjualan' => 'Stok tidak mencukupi. Stok tersisa: ' . $barangBaru->stok])->withInput();
        }
        DB::transaction(function () use ($penjualan, $barangLama, $barangBaru, $jumlahLama, $jumlahBaru, $validatedData) {
            if ($barangLama) {
                $barangLama->increment('stok', $jumlahLama);
            }
            
            $penjualan->update([
                'barang_id' => $barangBaru->id,
                'jumlah_penjualan' => $jumlahBaru,
                'harga_saat_transaksi' => $barangBaru->harga,
                'total_harga' => $barangBaru->harga * $jumlahBaru,
                'tgl_jual' => $validatedData['tgl_jual'],
            ]);
            $barangBaru->decrement('stok', $jumlahBaru);
        });
        return redirect()->route('penjualans.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            if ($penjualan->barang) {
                $penjualan->barang->increment('stok', $penjualan->jumlah_penjualan);
            }
            $penjualan->delete();
        });
        return redirect()->route('penjualans.index')->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $penjualans = Penjualan::with('barang')
            ->whereHas('barang', function ($q) use ($query) {
                $q->where('nama_barang', 'LIKE', "%{$query}%");
            })
            ->latest('tgl_jual')
            ->get();

        return view('penjualan._penjualan_rows', [
            'penjualans' => $penjualans,
            'is_search' => true
        ]);
    }

    // METHOD BARU UNTUK LAPORAN PDF
    public function generateReport(Request $request)
{
    $query = Penjualan::query()->with('barang')->latest('tgl_jual');
    
    $filterText = "Semua Penjualan";
    $fileNameSuffix = "Semua";

    if ($request->filled('filter_tanggal')) {
        $tanggal = Carbon::parse($request->filter_tanggal);
        $query->whereDate('tgl_jual', $tanggal);
        $filterText = "Tanggal " . $tanggal->isoFormat('D MMMM YYYY');
        $fileNameSuffix = $tanggal->format('Y-m-d');
    }
    elseif ($request->filled('filter_bulan')) {
        $bulan = Carbon::parse($request->filter_bulan);
        $query->whereMonth('tgl_jual', $bulan->month)
            ->whereYear('tgl_jual', $bulan->year);
        $filterText = "Bulan " . $bulan->isoFormat('MMMM YYYY');
        $fileNameSuffix = $bulan->format('Y-m');
    }
    elseif ($request->filled('filter_tahun')) {
        $tahun = $request->filter_tahun;
        $query->whereYear('tgl_jual', $tahun);
        $filterText = "Tahun " . $tahun;
        $fileNameSuffix = $tahun;
    }

    if ($request->filled('search')) {
        $query->whereHas('barang', function ($q) use ($request) {
            $q->where('nama_barang', 'like', '%' . $request->search . '%');
        });
    }

    $penjualans = $query->get();
    $totalPendapatan = $penjualans->sum('total_harga');

    $data = [
        'penjualans'      => $penjualans,
        'totalPendapatan' => $totalPendapatan,
        'filterText'      => "Laporan " . $filterText,
    ];

    $pdf = Pdf::loadView('reports.penjualan_pdf', $data);
    $pdf->setPaper('A4', 'portrait');

    $fileName = 'Laporan Penjualan ' . $fileNameSuffix . '.pdf';
    
    return $pdf->download($fileName);
}
}