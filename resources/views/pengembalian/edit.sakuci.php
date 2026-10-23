@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Edit Data Pengembalian</h1>

 <form action="{{ route('pengembalian.update', ['id_pengembalian' => $pengembalian->id_pengembalian]) }}" method="POST">
     @csrf
     @method('PUT')

     <div class="form-group mb-3">
         <label for="peminjaman"> Peminjaman </label>
         <input
          type="text"
          id="peminjaman"
          class="form-control"
          value="#{{ $pinjam->id_peminjaman }} | {{ $users[$pinjam->id_user]->name ?? 'User '.$pinjam->id_user }} | {{ $buku[$pinjam->id_buku]->judul ?? 'Buku '.$pinjam->id_buku }}"
          disabled>
     </div>

     <div class="form-group mb-3">
         <label for="tanggal_dikembalikan"> Tanggal Dikembalikan </label>
         <input
          type="date"
          name="tanggal_dikembalikan"
          id="tanggal_dikembalikan"
          class="form-control"
          value="{{ $pengembalian->tanggal_dikembalikan }}"
          required>
     </div>

     <div class="mb-3">
        <label for="kondisi_buku" class="form-label admin-label">Kondisi Buku</label>
        <select name="kondisi_buku" id="kondisi_buku" class="form-select" required>
            <option value="baik"   {{ $pengembalian->kondisi_buku === 'baik' ? 'selected' : '' }}>Baik</option>
            <option value="rusak"  {{ $pengembalian->kondisi_buku === 'rusak' ? 'selected' : '' }}>Rusak</option>
            <option value="hilang" {{ $pengembalian->kondisi_buku === 'hilang' ? 'selected' : '' }}>Hilang</option>
        </select>
     </div>

     <div class="form-group mb-3">
         <label for="denda"> Denda (dihitung otomatis) </label>
         <input
          type="text"
          id="denda"
          class="form-control"
          value="Rp{{ number_format($pengembalian->denda, 0, ',', '.') }}"
          disabled>
     </div>

     <div class="form-group mb-3">
         <label for="catatan_admin"> Catatan </label>
         <textarea
          name="catatan_admin"
          id="catatan_admin"
          class="form-control"
          rows="3">{{ $pengembalian->catatan_admin }}</textarea>
     </div>

       <button type="submit" class="btn btn-primary">update</button>
       <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Batal</a>
</form>
</div>

@endsection