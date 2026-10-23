<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\DataBuku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;

class PeminjamanController extends Controller
{
    // Tarif denda per hari keterlambatan
    private const DENDA_PER_HARI = 1000;

    // Lama pinjam (hari), dihitung sejak admin menyetujui
    private const LAMA_PINJAM_HARI = 7;

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
            'status'                => 'required|in:diajukan,dipinjam,dikembalikan',
            'denda'                 => 'nullable|numeric|min:0',
        ];
    }

    // Peminjaman yang masih berjalan + pengajuan siswa yang menunggu persetujuan
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(5);

        // Pengajuan siswa yang belum di-ACC admin
        $pengajuan = Peminjaman::where('status', 'diajukan')
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'pengajuan'  => $pengajuan,
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

    // Hapus data peminjaman (juga dipakai untuk menolak pengajuan siswa)
    public function destroy(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $ditolak = $peminjaman->status === 'diajukan';
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with(
            'success',
            $ditolak ? 'Pengajuan ditolak.' : 'Data peminjaman berhasil dihapus.'
        );
    }

    // Admin menyetujui pengajuan siswa. Baru setelah ini buku resmi dipinjam,
    // dan hitungan 7 hari (serta denda) mulai dari hari persetujuan.
    public function setujui(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        if ($peminjaman->status !== 'diajukan') {
            return redirect()->route('peminjaman.index')->with('error', 'Pengajuan ini sudah diproses.');
        }

        $peminjaman->update([
            'status'                => 'dipinjam',
            'tanggal_pinjam'        => date('Y-m-d'),
            'tanggal_wajib_kembali' => date('Y-m-d', strtotime('+' . self::LAMA_PINJAM_HARI . ' days')),
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan disetujui. Buku resmi dipinjam siswa.');
    }

    // Tandai buku sudah dikembalikan
    public function kembalikan(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);

        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        $hariIni = date('Y-m-d');
        $denda = 0;

        if ($hariIni > $peminjaman->tanggal_wajib_kembali) {
            $selisih = strtotime($hariIni) - strtotime($peminjaman->tanggal_wajib_kembali);
            $hariTerlambat = (int) ceil($selisih / 86400);
            $denda = $hariTerlambat * self::DENDA_PER_HARI;
        }

        $peminjaman->update([
            'status'               => 'dikembalikan',
            'tanggal_dikembalikan' => $hariIni,
            'denda'                => $denda,
        ]);

        // Catat juga ke tabel pengembalian supaya muncul di riwayat pengembalian
        Pengembalian::create([
            'id_peminjaman'        => $peminjaman->id_peminjaman,
            'tanggal_dikembalikan' => $hariIni,
            'kondisi_buku'         => 'baik',
            'denda'                => $denda,
            'catatan_admin'        => null,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
    }
}