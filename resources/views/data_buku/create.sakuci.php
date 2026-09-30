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

    /* Kartu form: punggung buku di sisi kiri */
    .form-kartu {
        background: var(--pk-card);
        border-left: 14px solid var(--pk-rose);
        border-bottom: 3px solid var(--pk-line);
        border-radius: 10px;
        padding: 1.75rem 1.75rem 1.5rem;
    }

    .grup { margin-bottom: 1.1rem; }
    .baris { display: grid; grid-template-columns: 1fr 1fr; gap: 0 1rem; }
    .grup label { display: block; font-weight: 700; font-size: .9rem; margin-bottom: .35rem; }
    .isian {
        width: 100%; font-family: inherit; font-size: 1rem; color: var(--pk-ink);
        background: #fff; border: 2px solid var(--pk-line); border-radius: 999px;
        padding: .6rem 1.1rem; transition: border-color .15s, box-shadow .15s;
    }
    select.isian { cursor: pointer; }
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

    @media (max-width: 560px) {
        .baris { grid-template-columns: 1fr; }
    }
</style>

<div class="katalog">

    <div class="katalog-head">
        <h1>Tambah Buku</h1>
        <p>Isi data buku baru untuk rak kamu</p>
    </div>

    <form action="{{ route('data_buku.store') }}" method="POST" class="form-kartu">
        @csrf

        <div class="grup">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" id="id_kategori" class="isian {{ errors()->has('id_kategori') ? 'salah' : '' }}" required>
                <option value="">-- Pilih Kategori Disini --</option>
                @foreach($daftarKategori as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="grup">
            <label for="judul_buku">Judul Buku</label>
            <input type="text" name="judul_buku" id="judul_buku" class="isian {{ errors()->has('judul_buku') ? 'salah' : '' }}" value="{{ old('judul_buku') }}" required>
            @error('judul_buku') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="baris">
            <div class="grup">
                <label for="pengarang">Pengarang</label>
                <input type="text" name="pengarang" id="pengarang" class="isian {{ errors()->has('pengarang') ? 'salah' : '' }}" value="{{ old('pengarang') }}" required>
                @error('pengarang') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="grup">
                <label for="penerbit">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" class="isian {{ errors()->has('penerbit') ? 'salah' : '' }}" value="{{ old('penerbit') }}" required>
                @error('penerbit') <div class="galat">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grup">
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="text" inputmode="numeric" name="tahun_terbit" id="tahun_terbit" class="isian {{ errors()->has('tahun_terbit') ? 'salah' : '' }}" value="{{ old('tahun_terbit') }}" required>
            @error('tahun_terbit') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-pink">Simpan</button>
    </form>

</div>

@endsection
