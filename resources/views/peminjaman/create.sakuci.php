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
    .katalog { font-family: 'DM Sans', sans-serif; color: var(--pk-ink); max-width: 760px; margin: 2rem auto 3rem; padding: 0 1rem; }

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
        <h1>Catat Peminjaman</h1>
        <p>Isi siapa yang meminjam dan buku apa yang dipinjam</p>
    </div>

    <form action="{{ route('peminjaman.store') }}" method="POST" class="form-kartu">
        @csrf
        @php
            $p = $peminjaman ?? null;
            $tglPinjam = old('tanggal_pinjam') ?: substr((string) ($p->tanggal_pinjam ?? date('Y-m-d')), 0, 10);
            $tglKembali = old('tanggal_wajib_kembali') ?: substr((string) ($p->tanggal_wajib_kembali ?? date('Y-m-d', strtotime('+7 days'))), 0, 10);
            $statusNow = old('status') ?: ($p->status ?? 'dipinjam');
            $dendaNow = old('denda') ?: ($p->denda ?? 0);
            $userNow = old('id_user') ?: ($p->id_user ?? '');
            $bukuNow = old('id_buku') ?: ($p->id_buku ?? '');
        @endphp

        <div class="baris">
            <div class="grup">
                <label for="id_user">Peminjam</label>
                <select name="id_user" id="id_user" class="isian {{ errors()->has('id_user') ? 'salah' : '' }}" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($daftarUser as $u)
                        @php $uid = $u->id_user ?? $u->id; @endphp
                        <option value="{{ $uid }}" {{ $userNow == $uid ? 'selected' : '' }}>{{ $u->username ?? $u->name }}</option>
                    @endforeach
                </select>
                @error('id_user') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="grup">
                <label for="id_buku">Buku</label>
                <select name="id_buku" id="id_buku" class="isian {{ errors()->has('id_buku') ? 'salah' : '' }}" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($daftarBuku as $b)
                        <option value="{{ $b->id_buku }}" {{ $bukuNow == $b->id_buku ? 'selected' : '' }}>{{ $b->judul_buku }}</option>
                    @endforeach
                </select>
                @error('id_buku') <div class="galat">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="baris">
            <div class="grup">
                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="isian {{ errors()->has('tanggal_pinjam') ? 'salah' : '' }}" value="{{ $tglPinjam }}" required>
                @error('tanggal_pinjam') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="grup">
                <label for="tanggal_wajib_kembali">Batas Kembali</label>
                <input type="date" name="tanggal_wajib_kembali" id="tanggal_wajib_kembali" class="isian {{ errors()->has('tanggal_wajib_kembali') ? 'salah' : '' }}" value="{{ $tglKembali }}" required>
                @error('tanggal_wajib_kembali') <div class="galat">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="baris">
            <div class="grup">
                <label for="status">Status</label>
                <select name="status" id="status" class="isian {{ errors()->has('status') ? 'salah' : '' }}" required>
                    <option value="dipinjam" {{ $statusNow === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ $statusNow === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
                @error('status') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="grup">
                <label for="denda">Denda (Rp)</label>
                <input type="number" min="0" step="1" name="denda" id="denda" class="isian {{ errors()->has('denda') ? 'salah' : '' }}" value="{{ $dendaNow }}">
                @error('denda') <div class="galat">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn-pink">Simpan</button>
    </form>

</div>

@endsection