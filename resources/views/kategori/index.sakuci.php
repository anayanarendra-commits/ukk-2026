@extends('layouts.app')

@section('title',config('app.name') . ' --kerangka PHP ringan')

@section ('content')

<div class= 'container'>
    <h1>Daftar Buku</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm mb-3">Tambah Buku</a>
</div>
<table class="table table-striped table-hover">
    <th>
        <tr>
            <th>no</th>
            <th>nama_kategori</th>
            <th>kode</th>
            <th>keterangan</th>
            <th>Aksi</th>
        </tr>
    </th>
    <tbody>
        @php $no=1; @endphp
        @foreach($data as $d)
        <tr>
            <td> {{ $no++ }}</td>
            <td> {{ $d->nama_kategori }} </td>
            <td> {{ $d-> kode }} </td>
            <td> {{ $d->keterangan }} </td>
            <td><a href="{{ route('kategori.edit', ['id_kategori' => $d->id_kategori]) }}" class="btn btn-sm btn-success">Edit</a>
              <form action="{{ route('kategori.destroy', ['id_kategori' => $d->id_kategori]) }}" method="POST"  class="d-inline">
                @csrf 
                @method('DELETE')
                <button type="sumbit" class="btn btn-sm btn-danger">Hapus</button>
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $data->links()!!}

@endsection