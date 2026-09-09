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
            <th>judul_buku</th>
            <th>pengarang</th>
            <th>penebit</th>
            <th>tahun_terbit</th>
            <th>Aksi</th>
        </tr>
    </th>
    <tbody>
        @php $no=1; @endphp
        @foreach($data as $d)
        <tr>
            <td> {{ $no++ }}</td>
            <td> {{ $d->judul_buku }} </td>
            <td> {{ $d->pengarang }} </td>
            <td> {{ $d->penerbit }} </td>
            <td> {{ $d->tahun_terbit }}</td>
            <td>
            <button class="btn btn-sm btn-success">Edit</button>
            <button class="btn btn-sm btn-danger">Hapus</button>
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $data->links()!!}

@endsection