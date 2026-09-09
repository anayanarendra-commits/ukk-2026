<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $data= Kategori::orderBy('id_buku', 'desc')->paginate(10);
        return view('kategori.index', compact('data'));
    }
    public function create(Request $request)
    {
        return view('Kategori.create');
    }
     public function store(Request $request)
     {
        $data = $request->validate([
            'judul_buku' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
        ]);

        Kategori::create($data);
        return redirect(route('kategori.index'))->with('success', 'Kategori berhasil ditambahkan.');
     }
     
      function edit(Request $request, $id_kategori)
     {
        $data = $request->all();

        $kategori = kategori::FindOrFail($id_buku);
        $kategori->update($data);
        return redirect(route('kategori.index'))->with('success', 'kategori berhasil diubah.');
     }
}