<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Barang Baru') }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    @endpush

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('barangs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input id="nama_barang" name="nama_barang" type="text" class="form-input" value="{{ old('nama_barang') }}" required>
                        @error('nama_barang') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input id="harga" name="harga" type="number" class="form-input" value="{{ old('harga') }}" required>
                        @error('harga') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input id="stok" name="stok" type="number" class="form-input" value="{{ old('stok') }}" required>
                        @error('stok') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto_barang">Foto Barang (Opsional)</label>
                        <input id="foto_barang" name="foto_barang" type="file" class="form-file-input">
                        @error('foto_barang') <p class="input-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('barangs.index') }}" class="cancel-button">Batal</a>
                    <button type="submit" class="submit-button">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>