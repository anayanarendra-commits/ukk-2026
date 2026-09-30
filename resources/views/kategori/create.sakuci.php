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
    .katalog { font-family: 'DM Sans', sans-serif; color: var(--pk-ink); max-width: 720px; margin: 2rem auto 3rem; padding: 0 1rem; }

    .katalog-head {
        background: var(--pk-card);
        border: 2px dashed var(--pk-rose);
        border-radius: 18px 18px 18px 4px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
    }
    .katalog-head h1 { font-family: 'Fraunces', serif; font-weight: 600; font-size: 2.2rem; margin: 0; letter-spacing: -.5px; }
    .katalog-head p { margin: .25rem 0 0; color: var(--pk-deep); font-size: .95rem; }

    .form-kartu {
        background: var(--pk-card);
        border-left: 14px solid var(--pk-rose);
        border-bottom: 3px solid var(--pk-line);
        border-radius: 10px;
        padding: 1.75rem 1.75rem 1.5rem;
    }

    .grup { margin-bottom: 1.1rem; }
    .grup label { display: block; font-weight: 700; font-size: .9rem; margin-bottom: .35rem; }
    .isian {
        width: 100%; font-family: inherit; font-size: 1rem; color: var(--pk-ink);
        background: #fff; border: 2px solid var(--pk-line); border-radius: 999px;
        padding: .6rem 1.1rem; transition: border-color .15s, box-shadow .15s;
    }
    .isian:focus { outline: none; border-color: var(--pk-deep); box-shadow: 0 0 0 4px var(--pk-soft); }
    .isian.salah { border-color: var(--pk-deep); }
    .galat { color: var(--pk-deep); font-size: .85rem; margin: .35rem 0 0 1rem; font-weight: 500; }

    .btn-pink {
        display: inline-block; cursor: pointer; font-family: inherit; font-size: 1rem;
        background: var(--pk-deep); color: #fff; border: 0; border-radius: 999px;
        padding: .65rem 2rem; font-weight: 700; margin-top: .75rem;
        box-shadow: 0 4px 0 #b84f65; transition: transform .1s, box-shadow .1s;
    }
    .btn-pink:hover { transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }
    .btn-pink:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 2px; }
</style>

<div class="katalog">

    <div class="katalog-head">
        <h1>Tambah Kategori</h1>
        <p>Buat kelompok baru supaya buku mudah dicari</p>
    </div>

    <form action="{{ route('kategori.store') }}" method="POST" class="form-kartu">
        @csrf

        <div class="grup">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="isian {{ errors()->has('nama_kategori') ? 'salah' : '' }}" value="{{ old('nama_kategori') }}" required>
            @error('nama_kategori') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="grup">
            <label for="kode">Kode</label>
            <input type="text" name="kode" id="kode" class="isian {{ errors()->has('kode') ? 'salah' : '' }}" value="{{ old('kode') }}" required>
            @error('kode') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="grup">
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="isian {{ errors()->has('keterangan') ? 'salah' : '' }}" value="{{ old('keterangan') }}" required>
            @error('keterangan') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-pink">Simpan</button>
    </form>

</div>

@endsection
