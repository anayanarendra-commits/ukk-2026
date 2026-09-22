@extends('layouts.app')

@section('content')

<h1>Tambah Buku</h1>
<form action="{{ route('kategori.store') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label for="nama_kategori">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required>
    </div>

    <div class="form-group mb-3">
        <label for="kode">Kode</label>
    <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="keterangan">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@endsection