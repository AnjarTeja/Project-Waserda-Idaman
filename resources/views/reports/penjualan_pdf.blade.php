<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $filterText }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .page-border {
            border: 2px solid #2F4F4F;
            padding: 15px;
            height: 95%;
        }
        .main-header {
            background-color: #2F4F4F;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .sub-header {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .sub-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sub-header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        /* PERUBAHAN DI SINI: text-align default menjadi center */
        .table th, .table td { 
            padding: 8px; 
            text-align: center; /* <-- DIUBAH */
            border-bottom: 1px solid #008080;
        }
        .table thead th { 
            background-color: #2F4F4F;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 2px solid #000;
        }
        .table tbody tr:last-child td {
            border-bottom: 2px solid #000;
        }
        .table tfoot td {
            font-weight: bold;
            border-bottom: none;
        }
        .text-right { 
            text-align: right; 
        }
        .text-center { 
            text-align: center; 
        }
        .font-bold { 
            font-weight: bold; 
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="page-border">
        <div class="main-header">
            WASERDA IDAMAN
        </div>
        <div class="sub-header">
            <h2>{{ $filterText }}</h2>
            <p>Dicetak pada : {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 25%;">Tanggal</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 20%;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualans as $penjualan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $penjualan->tgl_jual->format('d M Y, H:i') }}</td>
                        <td>{{ $penjualan->barang->nama_barang ?? 'N/A' }}</td>
                        <td>{{ $penjualan->jumlah_penjualan }}</td>
                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px;">Tidak ada data penjualan untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="font-bold text-right">TOTAL PENDAPATAN</td>
                    <td class="font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            © {{ date('Y') }} | Waserda Idaman | All Rights Reserved
        </div>
    </div>
</body>
</html>