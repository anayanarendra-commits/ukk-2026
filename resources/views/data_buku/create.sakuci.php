@extends('layouts.app')

@section('content')

<h1>Data Buku</h1>
<form action="{{ route('data_buku.store') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label for="judul_buku">Judul Buku</label>
        <input type="text" name="judul_buku" id="judul_buku" class="form-control" value="{{ old('judul_buku') }}" required>
    </div>

    <div class="form-group mb-3">
        <label for="pengarang">Pengarang</label>
    <input type="text" name="pengarang" id="pengarang" class="form-control" value="{{ old('pengarang') }}" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="penerbit">Penerbit</label>
        <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ old('penerbit') }}" required>
    </div>
  
    <div class="form-group mb-3">
        <label for="tahun_terbit">Tahun Terbit</label>
        <input type="text" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@endsection