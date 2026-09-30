@extends('layouts.app')

@section('title', config('app.name') . ' - Daftar Kategori')

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
    .katalog { font-family: 'DM Sans', sans-serif; color: var(--pk-ink); max-width: 1000px; margin: 2rem auto 3rem; padding: 0 1rem; }

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

    .btn-pink {
        display: inline-block; background: var(--pk-deep); color: #fff; border: 0; border-radius: 999px;
        padding: .6rem 1.4rem; font-weight: 700; text-decoration: none;
        box-shadow: 0 4px 0 #b84f65; transition: transform .1s, box-shadow .1s;
    }
    .btn-pink:hover { color: #fff; transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }

    /* Tabel: tiap baris jadi label kategori di rak */
    .rak { width: 100%; border-collapse: separate; border-spacing: 0 .7rem; }
    .rak thead th {
        text-align: left; font-weight: 500; font-size: .85rem; color: var(--pk-deep);
        padding: 0 1rem .1rem; border: 0; background: transparent;
    }
    .rak tbody td {
        text-align: left; padding: 1rem; vertical-align: middle;
        background: var(--pk-card); border: 0; border-bottom: 3px solid var(--pk-line);
    }
    .rak tbody td:first-child {
        border-left: 14px solid var(--pk-rose);
        border-radius: 10px 0 0 10px;
        width: 4.5rem; font-weight: 700; color: var(--pk-deep);
    }
    .rak tbody td:nth-child(2) { font-family: 'Fraunces', serif; font-size: 1.1rem; }
    .rak tbody td:last-child { border-radius: 0 10px 10px 0; }
    .rak tbody tr:nth-child(3n+2) td:first-child { border-left-color: var(--pk-deep); }
    .rak tbody tr:nth-child(3n+3) td:first-child { border-left-color: var(--pk-soft); }

    .kode {
        display: inline-block; background: var(--pk-soft); color: var(--pk-ink);
        border-radius: 999px; padding: .15rem .8rem; font-weight: 700; font-size: .85rem;
    }

    .aksi { display: flex; gap: .5rem; align-items: center; flex-wrap: nowrap; }
    .aksi form { margin: 0; display: inline-block; }
    .aksi-btn {
        display: inline-block; font-family: inherit; line-height: 1.3;
        border: 2px solid var(--pk-rose); background: transparent; color: var(--pk-ink);
        border-radius: 999px; padding: .25rem .9rem; font-size: .85rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: background .15s;
    }
    .aksi-btn:hover { background: var(--pk-soft); color: var(--pk-ink); }
    .aksi-btn.hapus { border-color: var(--pk-deep); color: var(--pk-deep); }
    .aksi-btn.hapus:hover { background: var(--pk-deep); color: #fff; }
    .aksi-btn:focus-visible, .btn-pink:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 2px; }

    .kosong { text-align: center !important; padding: 2.5rem 1rem !important; color: var(--pk-deep); border-left: 0 !important; border-radius: 10px !important; width: auto !important; font-family: 'DM Sans', sans-serif !important; font-size: 1rem !important; font-weight: 400 !important; }

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

    @media (max-width: 640px) {
        .rak thead { display: none; }
        .rak, .rak tbody, .rak tr, .rak td { display: block; width: 100%; }
        .rak tbody tr { margin-bottom: .9rem; }
        .rak tbody td { border-bottom: 0; border-radius: 0 !important; padding: .5rem 1rem; width: 100% !important; }
        .rak tbody td:first-child { border-radius: 10px 10px 0 0 !important; padding-top: 1rem; }
        .rak tbody td:last-child { border-radius: 0 0 10px 10px !important; padding-bottom: 1rem; border-bottom: 3px solid var(--pk-line); }
    }
</style>

<div class="katalog">

    <div class="katalog-head">
        <div>
            <h1>Daftar Kategori</h1>
            <p>Kelompok buku supaya rak tetap rapi</p>
        </div>
        <a href="{{ route('kategori.create') }}" class="btn-pink">+ Tambah Kategori</a>
    </div>

    <div style="overflow-x:auto">
        <table class="rak">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Kode</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($data as $d)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $d->nama_kategori }}</td>
                    <td><span class="kode">{{ $d->kode }}</span></td>
                    <td>{{ $d->keterangan }}</td>
                    <td>
                        <div class="aksi">
                            <a href="{{ route('kategori.edit', ['id_kategori' => $d->id_kategori]) }}" class="aksi-btn">Edit</a>
                            <form action="{{ route('kategori.destroy', ['id_kategori' => $d->id_kategori]) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="aksi-btn hapus">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="kosong">Belum ada kategori. Klik "Tambah Kategori" untuk membuat yang pertama.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {!! $data->links() !!}

</div>

@endsection
