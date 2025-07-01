<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $barangs = Barang::when($search, function ($query, $search) {
                return $query->where('nama_barang', 'LIKE', "%{$search}%");
            })
            ->oldest('id')
            ->paginate(10)
            ->withQueryString();
            
        return view('barang.index', compact('barangs'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $barangs = Barang::where('nama_barang', 'LIKE', "%{$query}%")
                        ->oldest('id')->get();
        
        return view('barang._barang_table_rows', [
            'barangs' => $barangs,
            'is_search' => true
        ]);
    }
    public function create()
    {
        return view('barang.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang',
            'foto_barang' => 'image|nullable|max:2048',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);
        if ($request->hasFile('foto_barang')) {
            $path = $request->file('foto_barang')->store('barang', 'public'); 
            $data['foto_barang'] = $path;
        }
        Barang::create($data);
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang,' . $barang->id,
            'foto_barang' => 'image|nullable|max:2048',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }
        
        $barang->update($data);
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto_barang) {
            Storage::disk('public')->delete($barang->foto_barang);
        }
        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}