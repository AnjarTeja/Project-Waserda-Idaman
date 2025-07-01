@forelse ($barangs as $barang)
    <tr>
        <td class="column-no">
            @if(isset($is_search) && $is_search)
                {{ $loop->iteration }}
            @else
                {{ ($barangs->currentPage() - 1) * $barangs->perPage() + $loop->iteration }}
            @endif
        </td>
        <td class="column-foto">
            @if($barang->foto_barang)
                <img src="{{ asset('storage/' . $barang->foto_barang) }}" alt="{{ $barang->nama_barang }}">
            @else
                <span>-</span>
            @endif
        </td>
        <td class="column-nama">{{ $barang->nama_barang }}</td>
        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
        <td>{{ $barang->stok }}</td>
        <td class="column-aksi">
            <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                <a href="{{ route('barangs.edit', $barang) }}" class="action-button edit" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                </a>
                <form action="{{ route('barangs.destroy', $barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-button delete" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.02-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr class="empty-row">
        <td colspan="6">Tidak ada barang yang cocok dengan pencarian Anda.</td>
    </tr>
@endforelse