@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Edit Kategori</h1>

 <form action="{{ route('kategori.update', ['id_kategori' => $kategori->id_kategori]) }}" method="POST">
     @csrf
     @method('PUT')

     <div class="form-group mb-3">
         <label for="nama_kategori"> Nama Kategori </label>
         <input
           type="text"
           name="nama_kategori"
           id="nama_kategori"
           class="form-control"
           value="{{ $kategori->nama_kategori}}"
           required>
     </div>

     <div class="form-group mb-3">
        <label for="kode"> Kode </label>
         <input
          type="text"
          name="kode"
          id="kode"
          class="form-control"
          value="{{ $kategori->kode }}"
          required>
     </div>

      <div class="form-group mb-3">
         <label for="keterangan"> Keterangan </label>
         <input
          type="text"
          name="keterangan"
          id="keterangan"
          class="form-control"
          value="{{ $kategori->keterangan }}"
          required>
     </div>
    
       <button type="submit" class="btn btn-primary">update</button>
      <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
</form>
</div>
 
@endsection