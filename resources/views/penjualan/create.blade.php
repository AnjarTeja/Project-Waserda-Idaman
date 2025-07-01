<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catat Transaksi Penjualan Baru') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    @endpush

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('penjualans.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    
                    <div class="form-group col-span-2">
                        <label for="barang_id">Pilih Barang</label>
                        <select id="barang_id" name="barang_id" class="form-input" required>
                            <option value="" disabled selected>-- Pilih Barang yang Terjual --</option>
                            @foreach($barangs as $barang)
                                {{-- Menambahkan atribut data-stok --}}
                                <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }} (Rp {{ number_format($barang->harga, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id') <p class="input-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-span-2">
                        <label for="jumlah_penjualan">Jumlah Terjual</label>
                        <input id="jumlah_penjualan" name="jumlah_penjualan" type="number" class="form-input" value="{{ old('jumlah_penjualan', 1) }}" required min="1">
                        @error('jumlah_penjualan') <p class="input-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('penjualans.index') }}" class="cancel-button">Batal</a>
                    <button type="submit" class="submit-button">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangSelect = document.getElementById('barang_id');
            const jumlahInput = document.getElementById('jumlah_penjualan');
            function updateMaxJumlah() {
                const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                const stok = selectedOption.getAttribute('data-stok');
                if (stok) {
                    jumlahInput.setAttribute('max', stok);
                } else {
                    jumlahInput.removeAttribute('max');
                }
            }
            updateMaxJumlah();
            barangSelect.addEventListener('change', updateMaxJumlah);
        });
    </script>
    @endpush
</x-app-layout>