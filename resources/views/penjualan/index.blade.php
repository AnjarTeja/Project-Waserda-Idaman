<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Penjualan') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/penjualan.css') }}">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="filter-card">
                <form action="{{ route('penjualans.index') }}" method="GET">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label for="filter_tanggal">Tgl. & Omset Harian:</label>
                            <input type="text" id="filter_tanggal" name="filter_tanggal" class="filter-input" placeholder="Pilih tanggal..." value="{{ request('filter_tanggal') }}">
                            @if(request('filter_tanggal') && isset($totalOmset))
                                <div class="mt-2 text-sm text-green-600">Omset: <span class="font-bold">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span></div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="filter_bulan">Bulan Omset:</label>
                            <input type="month" id="filter_bulan" name="filter_bulan" class="filter-input" value="{{ request('filter_bulan') }}">
                            @if(request('filter_bulan') && isset($totalOmset))
                                <div class="mt-2 text-sm text-green-600">Omset: <span class="font-bold">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span></div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="filter_tahun">Tahun Omset:</label>
                            <input type="number" id="filter_tahun" name="filter_tahun" class="filter-input" placeholder="Contoh : 2025" value="{{ request('filter_tahun') }}">
                            @if(request('filter_tahun') && isset($totalOmset))
                                <div class="mt-2 text-sm text-green-600">Omset: <span class="font-bold">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span></div>
                            @endif
                        </div>
                        <div class="filter-buttons-group">
                            <button type="submit" class="filter-button">Terapkan</button>
                            <a href="{{ route('penjualans.index') }}" class="filter-button" style="background-color: #6b7280;">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <div class="search-wrapper">
                        <div class="search-input-container">
                            <span class="search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" id="penjualan-search-input" class="filter-input search-field" placeholder="Cari berdasarkan nama barang...">
                        </div>
                    </div>
                    <div class="action-buttons-group">
                        <a href="{{ route('penjualans.report', request()->query()) }}" target="_blank" class="add-button" style="background-color: #16a34a;">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v3h6v-3z" clip-rule="evenodd" /></svg>
                            <span>Cetak</span>
                        </a>
                        <a href="{{ route('penjualans.create') }}" class="add-button">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            <span>Catat Penjualan</span>
                        </a>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th class="column-no">No.</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-right">Total</th>
                                <th class="column-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="penjualan-table-body">
                            @include('penjualan._penjualan_rows', ['penjualans' => $penjualans])
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 pagination-container">
                    {{ $penjualans->links() }}
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalPenjualan = @json($tanggalPenjualan ?? []);
            flatpickr("#filter_tanggal", {
                dateFormat: "Y-m-d",
                enable: tanggalPenjualan,
            });

            const searchInput = document.getElementById('penjualan-search-input');
            const tableBody = document.getElementById('penjualan-table-body');
            const paginationContainer = document.querySelector('.pagination-container');
            let searchTimeout;

            searchInput.addEventListener('keyup', function () {
                const query = this.value;
                
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('penjualans.search') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.text())
                        .then(html => {
                            tableBody.innerHTML = html;
                            if (paginationContainer) {
                                paginationContainer.style.display = query ? 'none' : 'block';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }, 300);
            });
        });
    </script>
    @endpush
</x-app-layout>