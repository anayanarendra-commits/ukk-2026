@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Riwayat pengembalian</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php $ada = false; $no = 0; @endphp

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Peminjaman</th>
                    <th>Tanggal dikembalikan</th>
                    <th>Kondisi buku</th>
                    <th>Denda</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengembalian as $item)
                    @php $ada = true; $no++; @endphp
                    <tr>
                        <td>{{ $no }}</td>
                        <td>#{{ $item->id_peminjaman }}</td>
                        <td>{{ $item->tanggal_dikembalikan }}</td>
                        <td>{{ ucfirst($item->kondisi_buku) }}</td>
                        <td>
                            @if ((int) $item->denda > 0)
                                <span class="text-danger fw-bold">{{ rupiah($item->denda) }}</span>
                            @else
                                {{ rupiah(0) }}
                            @endif
                        </td>
                        <td>{{ $item->catatan_admin ?: '-' }}</td>
                    </tr>
                @endforeach

                @if (! $ada)
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data pengembalian.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
