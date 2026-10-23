<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;

class PengembalianController
{
    // Denda per hari keterlambatan (ubah sesuai aturan perpustakaan)
    private const DENDA_PER_HARI = 1000;

    // Daftar pengembalian
    public function index()
    {
        $pengembalian = Pengembalian::query()->paginate();

        return view('pengembalian.index', compact('pengembalian'));
    }

    // Form pengembalian: hanya peminjaman yang masih berstatus "dipinjam"
    public function create()
    {
        $daftarPeminjaman = Peminjaman::query()
            ->where('status', 'dipinjam')
            ->get();

        return view('pengembalian.create', compact('daftarPeminjaman'));
    }

    // Simpan pengembalian
    public function store($request)
    {
        $request->validate([
            'id_peminjaman'        => 'required|numeric',
            'tanggal_dikembalikan' => 'required|date',
            'kondisi_buku'         => 'required|in:baik,rusak,hilang',
            'catatan_admin'        => 'nullable',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);

        // Cegah pengembalian ganda
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        // Hitung denda keterlambatan
        $denda = $this->hitungDenda(
            $peminjaman->tanggal_wajib_kembali,
            $request->tanggal_dikembalikan
        );

        Pengembalian::create([
            'id_peminjaman'        => $peminjaman->id_peminjaman,
            'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            'kondisi_buku'         => $request->kondisi_buku,
            'denda'                => $denda,
            'catatan_admin'        => $request->catatan_admin,
        ]);

        // Update status peminjaman
        $peminjaman->update([
            'status'               => 'dikembalikan',
            'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            'denda'                => $denda,
        ]);

        return redirect()->route('peminjaman.riwayat')
            ->with('success', 'Pengembalian berhasil dicatat.');
    }

    // Hapus data pengembalian (status peminjaman dikembalikan ke "dipinjam")
    public function destroy($request, $id_pengembalian)
    {
        $pengembalian = Pengembalian::findOrFail($id_pengembalian);

        $peminjaman = Peminjaman::findOrFail($pengembalian->id_peminjaman);
        $peminjaman->update([
            'status'               => 'dipinjam',
            'tanggal_dikembalikan' => null,
            'denda'                => 0,
        ]);

        $pengembalian->delete();

        return redirect()->route('peminjaman.riwayat')
            ->with('success', 'Data pengembalian dihapus.');
    }

    // Hitung denda: hari terlambat x tarif per hari
    private function hitungDenda($tanggalWajib, $tanggalKembali): int
    {
        $wajib   = strtotime($tanggalWajib);
        $kembali = strtotime($tanggalKembali);

        if ($kembali <= $wajib) {
            return 0;
        }

        $hariTerlambat = (int) ceil(($kembali - $wajib) / 86400);

        return $hariTerlambat * self::DENDA_PER_HARI;
    }
}