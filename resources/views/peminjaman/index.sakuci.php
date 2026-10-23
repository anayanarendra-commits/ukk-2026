@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;1,500&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --pk-bg: #fff0f3;
        --pk-card: #fffafb;
        --pk-soft: #ffd6e0;
        --pk-rose: #f4a7b9;
        --pk-deep: #d9687f;
        --pk-ink: #6b2a3f;
        --pk-line: #f7c8d4;
    }

    body { background: var(--pk-bg) !important; margin: 0; }

    .katalog, .katalog * { box-sizing: border-box; }
    .katalog { font-family: 'DM Sans', sans-serif; color: var(--pk-ink); max-width: 1100px; margin: 2rem auto 3rem; padding: 0 1rem; }

    .katalog-head {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        background: var(--pk-card);
        border: 2px dashed var(--pk-rose);
        border-radius: 18px 18px 18px 4px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
    }
    .katalog-head h1 { font-family: 'Fraunces', serif; font-weight: 600; font-size: 2.2rem; margin: 0; letter-spacing: -.5px; }
    .katalog-head p { margin: .25rem 0 0; color: var(--pk-deep); font-size: .95rem; }
    .head-aksi { display: flex; gap: .75rem; flex-wrap: wrap; align-items: center; }

    .btn-pink {
        display: inline-block; background: var(--pk-deep); color: #fff; border: 0; border-radius: 999px;
        padding: .6rem 1.4rem; font-weight: 700; text-decoration: none;
        box-shadow: 0 4px 0 #b84f65; transition: transform .1s, box-shadow .1s;
    }
    .btn-pink:hover { color: #fff; transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }
    .btn-garis {
        display: inline-block; border: 2px solid var(--pk-rose); color: var(--pk-ink); background: transparent;
        border-radius: 999px; padding: .5rem 1.3rem; font-weight: 700; text-decoration: none; transition: background .15s;
    }
    .btn-garis:hover { background: var(--pk-soft); color: var(--pk-ink); }

    /* Notifikasi */
    .notif { border-radius: 12px; padding: .8rem 1.1rem; margin-bottom: 1.25rem; font-weight: 500; }
    .notif.sukses { background: var(--pk-soft); border: 2px solid var(--pk-rose); color: var(--pk-ink); }
    .notif.gagal { background: #fff; border: 2px solid var(--pk-deep); color: var(--pk-deep); }

    /* Bagian pengajuan siswa yang menunggu persetujuan */
    .blok-pengajuan {
        margin-bottom: 2.4rem; padding: 1.3rem 1.3rem .6rem;
        background: rgba(255, 214, 224, .35); border: 2px dashed var(--pk-rose); border-radius: 22px;
    }
    .blok-sedang { margin-top: .4rem; }
    .blok-judul { display: flex; align-items: center; gap: .6rem; margin: 0 0 .2rem; }
    .blok-judul h2 { font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.5rem; margin: 0; letter-spacing: -.3px; }
    .blok-judul .hitung {
        display: inline-block; min-width: 1.8rem; text-align: center; padding: .1rem .6rem; border-radius: 999px;
        background: var(--pk-deep); color: #fff; font-weight: 700; font-size: .85rem;
    }
    .blok-ket { margin: 0 0 .3rem; color: var(--pk-deep); font-size: .9rem; }

    /* Tabel: tiap baris jadi kartu peminjaman */
    .rak { width: 100%; border-collapse: separate; border-spacing: 0 .7rem; }
    .rak thead th {
        text-align: left; font-weight: 500; font-size: .85rem; color: var(--pk-deep);
        padding: 0 1rem .1rem; border: 0; background: transparent;
    }
    .rak thead th:last-child { text-align: right; }
    .rak tbody td {
        text-align: left; padding: 1rem; vertical-align: middle;
        background: var(--pk-card); border: 0; border-bottom: 3px solid var(--pk-line);
    }
    .rak tbody td:first-child {
        border-left: 14px solid var(--pk-rose);
        border-radius: 10px 0 0 10px;
        width: 4.5rem; font-weight: 700; color: var(--pk-deep);
    }
    .rak tbody td:nth-child(3) { font-family: 'Fraunces', serif; font-size: 1.05rem; }
    .rak tbody td:last-child { border-radius: 0 10px 10px 0; }
    .rak tbody tr:nth-child(3n+2) td:first-child { border-left-color: var(--pk-deep); }
    .rak tbody tr:nth-child(3n+3) td:first-child { border-left-color: var(--pk-soft); }

    .lencana {
        display: inline-block; border-radius: 999px; padding: .15rem .8rem;
        font-weight: 700; font-size: .8rem; white-space: nowrap;
    }
    .lencana.menunggu { background: #fff; border: 2px dashed var(--pk-rose); color: var(--pk-ink); }
    .lencana.dipinjam { background: var(--pk-soft); color: var(--pk-ink); }
    .lencana.telat { background: var(--pk-deep); color: #fff; margin-left: .4rem; }

    .aksi { display: flex; gap: .5rem; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }
    .aksi form { margin: 0; display: inline-block; }
    .aksi-btn {
        display: inline-block; font-family: inherit; line-height: 1.3;
        border: 2px solid var(--pk-rose); background: transparent; color: var(--pk-ink);
        border-radius: 999px; padding: .25rem .9rem; font-size: .85rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: background .15s;
    }
    .aksi-btn:hover { background: var(--pk-soft); color: var(--pk-ink); }
    .aksi-btn.kembali { background: var(--pk-rose); border-color: var(--pk-rose); }
    .aksi-btn.kembali:hover { background: var(--pk-deep); border-color: var(--pk-deep); color: #fff; }
    .aksi-btn.setujui { background: var(--pk-deep); border-color: var(--pk-deep); color: #fff; }
    .aksi-btn.setujui:hover { background: #b84f65; border-color: #b84f65; color: #fff; }
    .aksi-btn.hapus { border-color: var(--pk-deep); color: var(--pk-deep); }
    .aksi-btn.hapus:hover { background: var(--pk-deep); color: #fff; }
    .aksi-btn:focus-visible, .btn-pink:focus-visible, .btn-garis:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 2px; }

    .kosong { text-align: center !important; padding: 2.5rem 1rem !important; color: var(--pk-deep); border-left: 0 !important; border-radius: 10px !important; width: auto !important; font-family: 'DM Sans', sans-serif !important; font-size: 1rem !important; font-weight: 400 !important; }

    @media (max-width: 720px) {
        .rak thead { display: none; }
        .rak, .rak tbody, .rak tr, .rak td { display: block; width: 100%; }
        .rak tbody tr { margin-bottom: .9rem; }
        .rak tbody td { border-bottom: 0; border-radius: 0 !important; padding: .5rem 1rem; width: 100% !important; }
        .rak tbody td:first-child { border-radius: 10px 10px 0 0 !important; padding-top: 1rem; }
        .rak tbody td:last-child { border-radius: 0 0 10px 10px !important; padding-bottom: 1rem; border-bottom: 3px solid var(--pk-line); }
        .aksi { justify-content: flex-start; }
    }

    /* Pagination (tanpa bergantung Bootstrap) */
    .katalog nav { margin-top: 1.5rem; }
    .katalog .pagination { display: flex; justify-content: center; flex-wrap: wrap; gap: .4rem; list-style: none; padding: 0; margin: 0; }
    .katalog .pagination li { list-style: none; margin: 0; padding: 0; }
    .katalog .pagination li > a,
    .katalog .pagination li > span {
        display: block; min-width: 2.3rem; text-align: center; padding: .35rem .75rem;
        border-radius: 999px; border: 2px solid var(--pk-line); background: var(--pk-card);
        color: var(--pk-deep); font-weight: 700; text-decoration: none;
    }
    .katalog .pagination li > a:hover { background: var(--pk-soft); }
    .katalog .pagination li.active > span,
    .katalog .pagination li.active > a { background: var(--pk-deep); border-color: var(--pk-deep); color: #fff; }
    .katalog .pagination li.disabled > span { opacity: .45; }
    .katalog nav p.text-sm { display: none; }
</style>

@php
    // Pengajuan siswa yang belum di-ACC (disalin ke array supaya aman dihitung)
    $daftarPengajuan = [];
    foreach (($pengajuan ?? []) as $x) {
        $daftarPengajuan[] = $x;
    }

    // Nomor urut (1, 2, 3, ...) menggantikan ID database.
    // Tabel utama dipaginasi 5 per halaman, jadi nomor lanjut di halaman berikutnya.
    $halaman       = max(1, (int) ($_GET['page'] ?? 1));
    $no            = ($halaman - 1) * 5;
    $nomorAjuan    = 0;
@endphp

<div class="katalog">

    <div class="katalog-head">
        <div>
            <h1>Peminjaman Berjalan</h1>
            <p>Buku yang sedang dipinjam, dan pengajuan siswa yang menunggu persetujuan</p>
        </div>
        <div class="head-aksi">
            <a href="{{ route('peminjaman.riwayat') }}" class="btn-garis">Lihat riwayat</a>
            <a href="{{ route('peminjaman.create') }}" class="btn-pink">+ Catat peminjaman</a>
        </div>
    </div>

    {{-- Notifikasi sukses sudah ditampilkan oleh layout (warna hijau), jadi tidak diulang di sini --}}
    @if (session('error'))
        <div class="notif gagal">{{ session('error') }}</div>
    @endif

    {{-- Pengajuan siswa: buku baru resmi dipinjam setelah admin menyetujui --}}
    @if (count($daftarPengajuan) > 0)
        <div class="blok-pengajuan">
            <div class="blok-judul">
                <h2>Menunggu persetujuan</h2>
                <span class="hitung">{{ count($daftarPengajuan) }}</span>
            </div>
            <p class="blok-ket">Siswa belum bisa memakai buku ini sampai kamu menyetujuinya. Lama pinjam 7 hari dihitung dari hari persetujuan.</p>

            <div style="overflow-x:auto">
                <table class="rak">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($daftarPengajuan as $a)
                        @php $nomorAjuan++; @endphp
                        <tr>
                            <td>{{ $nomorAjuan }}</td>
                            <td>{{ $users[$a->id_user]->username ?? ($users[$a->id_user]->name ?? '-') }}</td>
                            <td>{{ $buku[$a->id_buku]->judul_buku ?? '-' }}</td>
                            <td>{{ $a->tanggal_pinjam }}</td>
                            <td>
                                <div class="aksi">
                                    <form action="{{ route('peminjaman.setujui', ['id_peminjaman' => $a->id_peminjaman]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="aksi-btn setujui" onclick="return confirm('Setujui pengajuan ini? Buku akan resmi dipinjam siswa.')">Setujui</button>
                                    </form>
                                    <form action="{{ route('peminjaman.destroy', ['id_peminjaman' => $a->id_peminjaman]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="aksi-btn hapus" onclick="return confirm('Tolak pengajuan ini? Pengajuan akan dihapus.')">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="blok-sedang">
        <div class="blok-judul">
            <h2>Sedang dipinjam</h2>
        </div>
        <p class="blok-ket">Buku yang sudah disetujui dan sedang dipinjam siswa.</p>
    </div>

    <div style="overflow-x:auto">
        <table class="rak">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal pinjam</th>
                    <th>Batas kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($peminjaman as $p)
                @php
                    $no++;
                    $terlambat = $p->status === 'dipinjam' && $p->tanggal_wajib_kembali < date('Y-m-d');
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $users[$p->id_user]->username ?? ($users[$p->id_user]->name ?? '-') }}</td>
                    <td>{{ $buku[$p->id_buku]->judul_buku ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>
                        {{ $p->tanggal_wajib_kembali }}
                        @if ($terlambat) <span class="lencana telat">Terlambat</span> @endif
                    </td>
                    <td>
                        @if ($p->status === 'menunggu_konfirmasi')
                            <span class="lencana menunggu">Menunggu konfirmasi</span>
                        @else
                            <span class="lencana dipinjam">Dipinjam</span>
                        @endif
                    </td>
                    <td>
                        <div class="aksi">
                            @if ($p->status === 'dipinjam')
                                <form action="{{ route('peminjaman.kembalikan', ['id_peminjaman' => $p->id_peminjaman]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="aksi-btn kembali" onclick="return confirm('Tandai buku ini sudah dikembalikan?')">Kembalikan</button>
                                </form>
                            @endif
                            <form action="{{ route('peminjaman.destroy', ['id_peminjaman' => $p->id_peminjaman]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="aksi-btn hapus" onclick="return confirm('Hapus data peminjaman ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="kosong">Belum ada peminjaman berjalan. Klik "Catat peminjaman" untuk menambahkan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {!! $peminjaman->links() !!}

</div>
@endsection