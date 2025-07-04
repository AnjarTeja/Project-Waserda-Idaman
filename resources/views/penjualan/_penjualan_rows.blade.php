@forelse ($penjualans as $penjualan)
    <tr>
        <td class="column-no">
            @if(isset($is_search) && $is_search)
                {{ $loop->iteration }}
            @else
                {{ ($penjualans->currentPage() - 1) * $penjualans->perPage() + $loop->iteration }}
            @endif
        </td>
        <td>
            <div class="font-semibold">{{ $penjualan->tgl_jual->format('d M Y') }}</div>
            <div class="text-gray-500 text-sm">{{ $penjualan->tgl_jual->format('H:i') }}</div>
        </td>
        <td class="font-semibold">{{ $penjualan->barang->nama_barang ?? 'Barang Dihapus' }}</td>
        <td class="text-center">{{ $penjualan->jumlah_penjualan }}</td>
        <td class="text-right">{{ 'Rp ' . number_format($penjualan->harga_saat_transaksi, 0, ',', '.') }}</td>
        <td class="font-semibold text-right">{{ 'Rp ' . number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        <td class="column-aksi">
            <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                <a href="{{ route('penjualans.edit', $penjualan) }}" class="action-button edit" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                </a>
                <form action="{{ route('penjualans.destroy', $penjualan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-button delete" title="Batalkan">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.02-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr class="empty-row">
        <td colspan="7">Tidak ada data penjualan yang ditemukan.</td>
    </tr>
@endforelse