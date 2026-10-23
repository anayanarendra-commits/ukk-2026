@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Catat pengembalian</h1>
        <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pengembalian.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_peminjaman" class="form-label">Peminjaman</label>
            <select name="id_peminjaman" id="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman --</option>
                @foreach ($daftarPeminjaman as $pj)
                    <option value="{{ $pj->id_peminjaman }}" {{ old('id_peminjaman') == $pj->id_peminjaman ? 'selected' : '' }}>
                        #{{ $pj->id_peminjaman }}
                        | {{ $users[$pj->id_user]->name ?? 'User '.$pj->id_user }}
                        | {{ $buku[$pj->id_buku]->judul ?? 'Buku '.$pj->id_buku }}
                        | Batas: {{ $pj->tanggal_wajib_kembali }}
                    </option>
                @endforeach
            </select>
            @if (count($daftarPeminjaman) === 0)
                <div class="form-text text-muted">Tidak ada peminjaman yang sedang dipinjam.</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="tanggal_dikembalikan" class="form-label">Tanggal dikembalikan</label>
            <input type="date" name="tanggal_dikembalikan" id="tanggal_dikembalikan"
                   class="form-control" value="{{ old('tanggal_dikembalikan') ?: date('Y-m-d') }}" required>
            <div class="form-text">Denda dihitung otomatis jika melewati batas kembali.</div>
        </div>

        <div class="mb-3">
            <label for="kondisi_buku" class="form-label">Kondisi buku</label>
            <select name="kondisi_buku" id="kondisi_buku" class="form-select" required>
                <option value="baik"   {{ old('kondisi_buku') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak"  {{ old('kondisi_buku') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="hilang" {{ old('kondisi_buku') === 'hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="catatan_admin" class="form-label">Catatan (opsional)</label>
            <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3">{{ old('catatan_admin') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection