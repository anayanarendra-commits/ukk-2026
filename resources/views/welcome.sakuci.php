@extends('layouts.app')

@section('title', config('app.name') . ' -- Kerangka PHP Ringan')

@section('content')

    {{-- Hero --}}
    <section class="text-center py-4 py-lg-5">

        <h1 class="display-5 fw-bold mb-3">
            Selamat Datang Di,<br class="d-none d-md-inline">
            <span class="text-brand">Peminjaman Buku Sakura</span>
        </h1>

        <p class="lead text-secondary mx-auto mb-4" style="max-width: 620px;">
            Route, Model, View, dan Controller dalam satu paket ringan.
            Cukup PHP OOP murni -- salin foldernya, jalankan, selesai.
        </p>
    </section>
@endsection