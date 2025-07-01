<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Waserda Idaman') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    <div class="dashboard-container">
        <!-- Salam Pembuka -->
        <div class="welcome-banner">
            <div>
                <h3 class="welcome-title">Selamat Datang, <strong>{{ Auth::user()->name }}!</strong></h3>
                <p class="welcome-subtitle">Berikut adalah ringkasan aktivitas warung Anda hari ini.</p>
            </div>
        </div>

        <!-- Grid untuk Kartu Metrik -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V6.375c0-.621.504-1.125 1.125-1.125h.375m18 0h-4.875a1.125 1.125 0 00-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h4.875c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125z" /></svg>
                </div>
                <div class="metric-content">
                    <p class="metric-label">Penjualan Hari Ini</p>
                    <p class="metric-value">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75c0-.231-.035-.454-.1-.664M6.75 7.5h1.5a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75h-1.5a.75.75 0 00-.75.75v.75c0 .414.336.75.75.75z" /></svg>
                </div>
                <div class="metric-content">
                    <p class="metric-label">Jumlah Transaksi Hari Ini</p>
                    <p class="metric-value">{{ $jumlahTransaksi }}</p>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                </div>
                <div class="metric-content">
                    <p class="metric-label">Barang Tersedia</p>
                    <p class="metric-value">{{ $barangTersedia }}</p>
                </div>
            </div>
        </div>

        <!-- Grid untuk Grafik dan Tabel -->
        <div class="main-grid">
            <div class="chart-container card flex flex-col">
                <h4 class="card-title">Grafik Penjualan 7 Hari Terakhir</h4>
    
            <div class="relative flex-1">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
            <div class="table-container card">
                <h4 class="card-title">Transaksi Terbaru</h4>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualanTerbaru as $penjualan)
                            <tr>
                                <td>{{ $penjualan->barang->nama_barang }}</td>
                                <td>{{ $penjualan->jumlah_penjualan }}</td>
                                <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                <td><span class="order-time">{{ $penjualan->tgl_jual->format('H:i') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            function formatRupiah(value) {
                if (value >= 1000000) {
                    return 'Rp ' + (value / 1000000) + ' Jt';
                }
                if (value >= 1000) {
                    return 'Rp ' + (value / 1000) + 'k';
                }
                return 'Rp ' + value;
            }

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labelGrafik),
                    datasets: [{
                        label: 'Penjualan',
                        data: @json($dataGrafik),
                        backgroundColor: 'rgba(139, 92, 246, 0.2)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(139, 92, 246, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9ca3af',
                                callback: function(value, index, values) {
                                    return formatRupiah(value);
                                }
                            },
                            grid: {
                                color: '#374151'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9ca3af'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            },
                            padding: 12,
                            boxPadding: 4,
                            backgroundColor: '#111827',
                            titleColor: '#e5e7eb',
                            bodyColor: '#d1d5db',
                            borderColor: '#374151',
                            borderWidth: 1,
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>