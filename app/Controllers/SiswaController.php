<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\DataBuku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;

class SiswaController extends Controller
{
    // Tarif denda per hari keterlambatan (ubah sesuai aturan perpustakaan)
    const DENDA_PER_HARI = 1000;

    private function idSaya()
    {
        $u = User::current();
        return $u->id_user ?? $u->id;
    }

    private function petaBuku()
    {
        $peta = [];
        foreach (DataBuku::all() as $b) {
            $peta[$b->id_buku] = $b;
        }
        return $peta;
    }

    // Hitung denda satu peminjaman: hari telat, total, dan sudah lunas atau belum
    private function infoDenda($p)
    {
        $hariIni = date('Y-m-d');
        $wajib   = substr((string) $p->tanggal_wajib_kembali, 0, 10);
        $lunas   = !empty($p->denda_lunas);
        $hari    = 0;

        if ($p->status === 'dipinjam' && $wajib !== '' && $wajib < $hariIni) {
            $hari = (int) ((strtotime($hariIni) - strtotime($wajib)) / 86400);
        } elseif ($p->status === 'dikembalikan' && !empty($p->tanggal_dikembalikan)) {
            $kembali = substr((string) $p->tanggal_dikembalikan, 0, 10);
            if ($wajib !== '' && $kembali > $wajib) {
                $hari = (int) ((strtotime($kembali) - strtotime($wajib)) / 86400);
            }
        }

        if ($lunas) {
            $total = (int) $p->denda;
        } elseif ($p->status === 'dipinjam') {
            $total = $hari * self::DENDA_PER_HARI;
        } else {
            $total = (int) $p->denda > 0 ? (int) $p->denda : $hari * self::DENDA_PER_HARI;
        }

        return ['hari' => $hari, 'total' => $total, 'lunas' => $lunas];
    }

    // Pencarian buku (berdasarkan judul)
    public function cari(Request $request)
    {
        $kata = trim($_GET['q'] ?? '');
        $hasil = [];

        foreach (DataBuku::all() as $b) {
            if ($kata === '' || stripos($b->judul_buku, $kata) !== false) {
                $hasil[] = $b;
            }
        }

        return view('siswa.cari', ['hasil' => $hasil, 'kata' => $kata]);
    }

    // Daftar kategori buku (hanya lihat)
    public function kategori(Request $request)
    {
        $daftar = Kategori::orderBy('nama_kategori', 'asc')->get();

        // Hitung jumlah buku di tiap kategori
        $jumlah = [];
        foreach (DataBuku::all() as $b) {
            $idKat = $b->id_kategori ?? null;
            if ($idKat !== null) {
                $jumlah[$idKat] = ($jumlah[$idKat] ?? 0) + 1;
            }
        }

        return view('siswa.kategori', ['daftar' => $daftar, 'jumlah' => $jumlah]);
    }

    // Daftar buku di dalam satu kategori
    public function bukuKategori(Request $request, $id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);

        $buku = [];
        foreach (DataBuku::all() as $b) {
            if (($b->id_kategori ?? null) == $id_kategori) {
                $buku[] = $b;
            }
        }

        return view('siswa.kategori_buku', ['kategori' => $kategori, 'buku' => $buku]);
    }

    // Halaman pengembalian: buku yang sedang dipinjam siswa yang login,
    // plus buku yang sudah dikembalikan tapi dendanya belum dibayar.
    // Bisa difilter berdasarkan kondisi dan judul.
    public function index(Request $request)
    {
        $status = trim($_GET['status'] ?? '');
        $kata   = trim($_GET['q'] ?? '');

        $buku    = $this->petaBuku();
        $hariIni = date('Y-m-d');

        $semua = Peminjaman::where('id_user', $this->idSaya())
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        $peminjaman = [];
        foreach ($semua as $p) {
            // Tanpa filter: tampilkan yang sedang dipinjam, dan yang sudah
            // dikembalikan tapi dendanya belum lunas.
            if ($status === '') {
                $belumLunas = $p->status === 'dikembalikan'
                    && (int) $p->denda > 0
                    && empty($p->denda_lunas);

                if ($p->status !== 'dipinjam' && !$belumLunas) {
                    continue;
                }
            }

            // Kondisi: diajukan / dipinjam / dikembalikan / terlambat
            if ($status === 'terlambat') {
                $wajib = substr((string) $p->tanggal_wajib_kembali, 0, 10);
                if ($p->status !== 'dipinjam' || $wajib >= $hariIni) {
                    continue;
                }
            } elseif ($status !== '' && $p->status !== $status) {
                continue;
            }

            // Judul buku
            if ($kata !== '') {
                $judul = isset($buku[$p->id_buku]) ? (string) $buku[$p->id_buku]->judul_buku : '';
                if (stripos($judul, $kata) === false) {
                    continue;
                }
            }

            $peminjaman[] = $p;
        }

        $denda = [];
        foreach ($peminjaman as $p) {
            $denda[$p->id_peminjaman] = $this->infoDenda($p);
        }

        return view('siswa.index', [
            'peminjaman' => $peminjaman,
            'denda'      => $denda,
            'buku'       => $buku,
            'status'     => $status,
            'kata'       => $kata,
        ]);
    }

    // Form pengajuan: buku sudah dipilih dari halaman kategori (?buku=ID),
    // jadi siswa langsung melihat tanggal pinjam dan batas kembali.
    public function create(Request $request)
    {
        $idBuku = trim($_GET['buku'] ?? '');

        // Belum pilih buku -> arahkan ke halaman kategori dulu
        if ($idBuku === '') {
            return redirect()->route('siswa.kategori');
        }

        $buku = DataBuku::findOrFail($idBuku);

        $kategori = null;
        if (!empty($buku->id_kategori)) {
            $kategori = Kategori::findOrFail($buku->id_kategori);
        }

        return view('siswa.form', [
            'buku'           => $buku,
            'kategori'       => $kategori,
            'tanggalPinjam'  => date('Y-m-d'),
            'tanggalKembali' => date('Y-m-d', strtotime('+7 days')),
        ]);
    }

    // Simpan pengajuan. Tanggal pinjam/kembali diatur admin saat menyetujui.
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_buku' => 'required|integer',
        ]);

        $data['id_user']               = $this->idSaya();
        $data['status']                = 'diajukan';
        $data['denda']                 = 0;
        $data['tanggal_pinjam']        = date('Y-m-d');
        $data['tanggal_wajib_kembali'] = date('Y-m-d', strtotime('+7 days'));

        Peminjaman::create($data);

        return redirect()->route('siswa.peminjaman.index')->with('success', 'Pengajuan peminjaman terkirim. Tunggu persetujuan petugas.');
    }

    // Siswa mengembalikan buku; denda dihitung otomatis kalau telat
    public function kembalikan(Request $request, $id_peminjaman)
    {
        $p = Peminjaman::findOrFail($id_peminjaman);

        if ($p->id_user != $this->idSaya()) {
            return redirect()->route('siswa.peminjaman.index')->with('error', 'Data ini bukan milikmu.');
        }

        if ($p->status !== 'dipinjam') {
            return redirect()->route('siswa.peminjaman.index')->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        // Hitung dulu selagi status masih 'dipinjam'
        $info = $this->infoDenda($p);

        $p->status               = 'dikembalikan';
        $p->tanggal_dikembalikan = date('Y-m-d');
        $p->denda                = $info['total'];

        // Denda telat: simpan jenis dan keterangannya supaya tampil di riwayat
        if ($info['total'] > 0) {
            $p->jenis_denda      = 'terlambat';
            $p->keterangan_denda = 'Terlambat ' . $info['hari'] . ' hari x Rp '
                . number_format(self::DENDA_PER_HARI, 0, ',', '.');
        }

        $p->save();

        // Catat juga ke tabel pengembalian supaya muncul di riwayat pengembalian (admin)
        Pengembalian::create([
            'id_peminjaman'        => $p->id_peminjaman,
            'tanggal_dikembalikan' => date('Y-m-d'),
            'kondisi_buku'         => 'baik',
            'denda'                => $info['total'],
            'catatan_admin'        => null,
        ]);

        $pesan = 'Buku berhasil dikembalikan.';
        if ($info['total'] > 0) {
            $pesan .= ' Kamu terlambat ' . $info['hari'] . ' hari, denda Rp '
                . number_format($info['total'], 0, ',', '.') . '.';
        }

        return redirect()->route('siswa.peminjaman.index')->with('success', $pesan);
    }

    // Dashboard siswa: ringkasan pinjaman, jatuh tempo terdekat, denda, dan aktivitas terbaru
    public function dashboard(Request $request)
    {
        $user = User::current();
        $nama = $user->username ?? ($user->name ?? 'siswa');

        $buku    = $this->petaBuku();
        $hariIni = date('Y-m-d');

        $semua = Peminjaman::where('id_user', $this->idSaya())
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        $dipinjam   = 0;
        $menunggu   = 0;
        $terlambat  = 0;
        $totalDenda = 0;
        $terdekat   = null;
        $aktivitas  = [];

        foreach ($semua as $p) {
            $judul = isset($buku[$p->id_buku]) ? (string) $buku[$p->id_buku]->judul_buku : '-';
            $wajib = substr((string) $p->tanggal_wajib_kembali, 0, 10);
            $telat = $p->status === 'dipinjam' && $wajib !== '' && $wajib < $hariIni;

            if ($p->status === 'dipinjam') {
                $dipinjam++;
                if ($telat) {
                    $terlambat++;
                }
                // Buku yang paling dekat jatuh temponya
                if ($wajib !== '' && ($terdekat === null || $wajib < $terdekat['wajib'])) {
                    $terdekat = [
                        'judul' => $judul,
                        'wajib' => $wajib,
                        'sisa'  => (int) round((strtotime($wajib) - strtotime($hariIni)) / 86400),
                    ];
                }
            } elseif ($p->status === 'diajukan') {
                $menunggu++;
            }

            // Denda yang belum dibayar (termasuk denda berjalan untuk buku yang telat)
            $info = $this->infoDenda($p);
            if (!$info['lunas'] && $info['total'] > 0) {
                $totalDenda += $info['total'];
            }

            if (count($aktivitas) < 5) {
                $aktivitas[] = [
                    'judul'   => $judul,
                    'status'  => (string) $p->status,
                    'telat'   => $telat,
                    'tanggal' => substr((string) $p->tanggal_pinjam, 0, 10),
                ];
            }
        }

        return view('siswa.dashboard', [
            'nama'       => $nama,
            'dipinjam'   => $dipinjam,
            'menunggu'   => $menunggu,
            'terlambat'  => $terlambat,
            'totalDenda' => $totalDenda,
            'terdekat'   => $terdekat,
            'aktivitas'  => $aktivitas,
        ]);
    }

    // Bayar denda langsung dari tabel transaksi
    public function bayarDenda(Request $request, $id_peminjaman)
    {
        $p = Peminjaman::findOrFail($id_peminjaman);

        if ($p->id_user != $this->idSaya()) {
            return redirect()->route('siswa.peminjaman.index')->with('error', 'Data ini bukan milikmu.');
        }

        $info = $this->infoDenda($p);

        if ($info['lunas'] || $info['total'] <= 0) {
            return redirect()->route('siswa.peminjaman.index')->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        $p->denda       = $info['total'];
        $p->denda_lunas = 1;
        $p->tanggal_bayar = date('Y-m-d');
        $p->save();

        return redirect()->route('siswa.peminjaman.index')
            ->with('success', 'Denda Rp ' . number_format($info['total'], 0, ',', '.') . ' berhasil dibayar. Terima kasih!');
    }
}