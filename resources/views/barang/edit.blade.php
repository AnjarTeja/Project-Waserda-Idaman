<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Barang: ') . $barang->nama_barang }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    @endpush

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('barangs.update', $barang) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input id="nama_barang" name="nama_barang" type="text" class="form-input" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                        @error('nama_barang') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input id="harga" name="harga" type="number" class="form-input" value="{{ old('harga', $barang->harga) }}" required>
                        @error('harga') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input id="stok" name="stok" type="number" class="form-input" value="{{ old('stok', $barang->stok) }}" required>
                        @error('stok') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto_barang">Ganti Foto Barang (Opsional)</label>
                        <input id="foto_barang" name="foto_barang" type="file" class="form-file-input">
                        @error('foto_barang') <p class="input-error">{{ $message }}</p> @enderror

                        @if ($barang->foto_barang)
                            <div class="current-photo-container">
                                <p>Foto Saat Ini:</p>
                                <img src="{{ asset('storage/' . $barang->foto_barang) }}" alt="{{ $barang->nama_barang }}">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('barangs.index') }}" class="cancel-button">Batal</a>
                    <button type="submit" class="submit-button">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>