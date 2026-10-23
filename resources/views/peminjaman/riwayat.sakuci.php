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
    .lencana.lunas { background: #e6f5ea; color: #2f7a4a; margin-left: .4rem; }
    .lencana.belum { background: #fff0e8; border: 2px dashed #f0a98a; color: #b4400f; margin-left: .4rem; }
    .denda-sel { white-space: nowrap; }

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

<div class="katalog">

    <div class="katalog-head">
        <div>
            <h1>Riwayat Pengembalian</h1>
            <p>Buku yang sudah kembali ke rak</p>
        </div>
        <div class="head-aksi">
            <a href="{{ route('peminjaman.index') }}" class="btn-garis">Peminjaman berjalan</a>
        </div>
    </div>

    @if (session('success'))
        <div class="notif sukses">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="notif gagal">{{ session('error') }}</div>
    @endif

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
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($peminjaman as $p)
                <tr>
                    <td>{{ $p->id_peminjaman }}</td>
                    <td>{{ $users[$p->id_user]->username ?? ($users[$p->id_user]->name ?? '-') }}</td>
                    <td>{{ $buku[$p->id_buku]->judul_buku ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $p->tanggal_wajib_kembali }}</td>
                    <td><span class="lencana dipinjam">Dikembalikan</span></td>
                    <td class="denda-sel">
                        Rp {{ number_format((float) ($p->denda ?? 0), 0, ',', '.') }}
                        @if ((float) ($p->denda ?? 0) > 0)
                            @if (!empty($p->denda_lunas))
                                <span class="lencana lunas">Lunas</span>
                            @else
                                <span class="lencana belum">Belum bayar</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <div class="aksi">
                            <form action="{{ route('peminjaman.destroy', $p->id_peminjaman) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="aksi-btn hapus" onclick="return confirm('Hapus data riwayat ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="kosong">Belum ada buku yang dikembalikan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {!! $peminjaman->links() !!}

</div>
@endsection