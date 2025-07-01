<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <link rel="stylesheet" href="{{ asset('css/table.css') }}">
            
            <div class="table-container">
                <div class="table-header">
                    <div class="search-container">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <input type="text" id="barang-search-input" class="search-input search-field" placeholder="Ketik nama barang untuk mencari...">
                    </div>
                    <a href="{{ route('barangs.create') }}" class="add-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        <span>Tambah Barang</span>
                    </a>
                </div>

                @if(session('success'))
                    <div class="success-notification">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="column-no">No.</th>
                                <th class="column-foto">Foto</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="column-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-table-body">
                            @include('barang._barang_table_rows', ['barangs' => $barangs])
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 pagination-container">
                    {{ $barangs->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('barang-search-input');
            const tableBody = document.getElementById('barang-table-body');
            const paginationContainer = document.querySelector('.pagination-container');
            let searchTimeout;

            searchInput.addEventListener('keyup', function () {
                const query = this.value;
                
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('barangs.search') }}?query=${encodeURIComponent(query)}`)
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