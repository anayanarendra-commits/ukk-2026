@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Edit Data Buku</h1>

 <form action="{{ route('data_buku.update', ['id_buku' => $data_buku->id_buku]) }}" method="POST">
     @csrf
     @method('PUT')

     <div class="mb-3">
        <label for="id_kategori" class="form-label admin-label">Kategori</label>
        <select name="id_kategori" id="id_kategori" class="form-select" required>
            <option value="">-- Pilih Kategori Disini --</option>
            @foreach($daftarKategori as $kat)
                <option value="<?= e($kat->id_kategori) ?>"><?= e($kat->nama_kategori) ?></option>
            @endforeach
        </select>
     </div>

     <div class="form-group mb-3">
         <label for="judul_buku"> Judul Buku </label>
         <input type="text" name="judul_buku" id="judul_buku" class="form-control" value="{{ $data_buku->judul_buku}}" required>
     </div>

     <div class="form-group mb-3">
        <label for="pengarang"> Pengarang </label>
         <input
          type="text"
          name="pengarang"
          id="pengarang"
          class="form-control"
          value="{{ $data_buku->pengarang }}"
          required>
     </div>

      <div class="form-group mb-3">
         <label for="penerbit"> Penerbit </label>
         <input
          type="text"
          name="penerbit"
          id="penerbit"
          class="form-control"
          value="{{ $data_buku->penerbit }}"
          required>
     </div>

     <div class="form-group mb-3">
         <label for="tahun_terbit"> Tahun Terbit</label>
         <input
          type="text"
          name="tahun_terbit"
          id="tahun_terbit"
          class="form-control"
          value="{{ $data_buku->tahun_terbit }}"
          required>
     </div>
    
       <button type="submit" class="btn btn-primary">update</button>
      <a href="{{ route('data_buku.index') }}" class="btn btn-secondary">Batal</a>
</form>
</div>
 
@endsection