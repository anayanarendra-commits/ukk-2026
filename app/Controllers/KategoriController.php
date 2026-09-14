<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $data= Kategori::orderBy('id_kategori', 'desc')->paginate(10);
        return view('kategori.index', compact('data'));
    }
    public function create(Request $request)
    {
        return view('Kategori.create');
    }
     public function store(Request $request)
     {
        $data = $request->validate([
            'nama_kategori' => 'required',
            'kode' => 'required',
            'keterangan' => 'required',
        ]);

        Kategori::create($data);
        return redirect(route('kategori.index'))->with('success', 'Kategori berhasil ditambahkan.');
     }
     
     public function edit(Request $request, $id_kategori)
     {
        $data = $request->all();

        $kategori = kategori::FindOrFail($id_kategori);
        return view('kategori.edit', compact('kategori'));
     }
     public function update(Request $request, $id_kategori)
     {
        $request->validate([
            'nama_kategori' =>'required',
            'kode' =>'required',
            'keterangan' =>'required',
        ]);
        $kategori = kategori::findOrfail($id_kategori);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'kode' => $request->kode,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
     }
     public function destroy(Request $request, $id_kategori)
     {
        $kategori = kategori::findOrfail($id_kategori);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus,');
     }
}