@extends('layouts.app')

@section('title',config('app.name') . ' --kerangka PHP ringan')

@section ('content')

<div class= 'container'>
    <h1>Data Buku</h1>
    <a href="{{ route('data_buku.create') }}" class="btn btn-primary btn-sm mb-3">Data Buku</a>
</div>
<table class="table table-striped table-hover">
    <th>
        <tr>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun_terbit</th>
            <th>Aksi</th>
        </tr>
    </th>
    <tbody>
        @php $no=1; @endphp
        @foreach($data_buku as $d)
        <tr>
            <td> {{ $d->judul_buku }} </td>
            <td> {{ $d-> pengarang}} </td>
            <td> {{ $d->penerbit }} </td>
            <td> {{ $d->tahun_terbit}}</td>
            <td><a href="{{ route('data_buku.edit', ['id_buku' => $d->id_buku]) }}" class="btn btn-sm btn-success">Edit</a>
              <form action="{{ route('data_buku.destroy', ['id_buku' => $d->id_buku]) }}" method="POST"  class="d-inline">
                @csrf 
                @method('DELETE')
                <button type="sumbit" class="btn btn-sm btn-danger">Hapus</button>
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $data_buku->links()!!}

@endsection