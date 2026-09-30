<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\DataBuku;
use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanController extends Controller
{
    // Daftar user dan buku, disusun supaya gampang dicari lewat id-nya
    private function petaUser()
    {
        $peta = [];
        foreach (User::all() as $u) {
            $peta[$u->id_user ?? $u->id] = $u;
        }
        return $peta;
    }

    private function petaBuku()
    {
        $peta = [];
        foreach (DataBuku::all() as $b) {
            $peta[$b->id_buku] = $b;
        }
        return $peta;
    }

    private function aturan()
    {
        return [
            'id_user'               => 'required|integer',
            'id_buku'               => 'required|integer',
            'tanggal_pinjam'        => 'required|date',
            'tanggal_wajib_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status'                => 'required|in:dipinjam,dikembalikan',
            'denda'                 => 'nullable|numeric|min:0',
        ];
    }

    // Peminjaman yang masih berjalan
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(5);

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'users'      => $this->petaUser(),
            'buku'       => $this->petaBuku(),
        ]);
    }

    // Peminjaman yang sudah dikembalikan
    public function riwayat(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'dikembalikan')
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        return view('peminjaman.riwayat', [
            'peminjaman' => $peminjaman,
            'users'      => $this->petaUser(),
            'buku'       => $this->petaBuku(),
        ]);
    }

    public function create(Request $request)
    {
        return view('peminjaman.create', [
            'daftarUser' => User::all(),
            'daftarBuku' => DataBuku::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->aturan());

        if (($validatedData['denda'] ?? '') === '') {
            $validatedData['denda'] = 0;
        }

        Peminjaman::create($validatedData);
        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam.');
    }

    public function edit($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        return view('peminjaman.edit', [
            'peminjaman' => $peminjaman,
            'daftarUser' => User::all(),
            'daftarBuku' => DataBuku::all(),
        ]);
    }

    public function update(Request $request, $id_peminjaman)
    {
        $validatedData = $request->validate($this->aturan());

        if (($validatedData['denda'] ?? '') === '') {
            $validatedData['denda'] = 0;
        }

        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update($validatedData);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman buku berhasil diperbarui.');
    }

    // Tandai buku sudah dikembalikan
    public function kembalikan(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->update(['status' => 'dikembalikan']);

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function destroy(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}