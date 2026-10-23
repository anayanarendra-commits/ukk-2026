@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $pengguna = \App\Models\User::current();
    $nama = $pengguna->username ?? 'pengguna';
    $peran = $pengguna->role ?? '';
@endphp

{{-- Siswa (role peminjam) langsung diarahkan ke dashboard sakura --}}
@if ($peran === 'peminjam')
    <meta http-equiv="refresh" content="0;url={{ url('/siswa/dashboard') }}">
    <script>window.location.replace('{{ url('/siswa/dashboard') }}');</script>
@endif

<div style="max-width: 720px; margin: 3rem auto; padding: 0 1rem; font-family: 'DM Sans', sans-serif;">
    <div style="background: var(--pk-card, #fffafb); border: 2px dashed var(--pk-rose, #f4a7b9); border-radius: 22px; padding: 1.75rem; color: var(--pk-ink, #6b2a3f);">
        <span style="display: inline-block; padding: .2rem .9rem; border-radius: 999px; font-size: .8rem; font-weight: 700; background: var(--pk-soft, #ffd6e0);">
            Role: {{ $peran }}
        </span>
        <h1 style="margin: .9rem 0 .3rem; font-size: 1.8rem;">Halo, {{ $nama }}</h1>
        <p style="margin: 0;">Ini halaman dashboard umum, bisa diakses semua role yang sudah login.</p>

        @if ($peran === 'peminjam')
            <p style="margin: 1rem 0 0;">
                Kamu akan dibawa ke dashboard siswa.
                <a href="{{ url('/siswa/dashboard') }}">Klik di sini kalau tidak pindah sendiri.</a>
            </p>
        @endif
    </div>
</div>
@endsection