@extends('layouts.app')

@section('title', 'Admin')

@section('content')

@php
    // Kelopak yang jatuh: [posisi kiri %, ukuran px, delay detik, durasi detik]
    $kelopak = [
        [4, 20, 0, 14], [14, 12, 4, 17], [24, 16, 8, 15], [36, 10, 2, 18],
        [47, 20, 6, 16], [58, 12, 10, 14], [68, 16, 1, 17], [78, 10, 5, 15],
        [87, 20, 9, 16], [95, 14, 3, 18],
    ];
@endphp

<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;1,500&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    /* Latar seluruh halaman */
    body {
        background-color: #ffeef3 !important;
        background-image:
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cdefs%3E%3Cpath id='p' d='M0 0C-6-4-6-13-2-15L0-13L2-15C6-13 6-4 0 0Z'/%3E%3Cg id='f'%3E%3Cuse href='%23p'/%3E%3Cuse href='%23p' transform='rotate(72)'/%3E%3Cuse href='%23p' transform='rotate(144)'/%3E%3Cuse href='%23p' transform='rotate(216)'/%3E%3Cuse href='%23p' transform='rotate(288)'/%3E%3C/g%3E%3C/defs%3E%3Cg fill='%23f4a7b9' fill-opacity='.28'%3E%3Cuse href='%23f' transform='translate(30 34)'/%3E%3Cuse href='%23f' transform='translate(90 94) scale(.7)'/%3E%3C/g%3E%3C/svg%3E"),
            radial-gradient(circle at 12% 8%, #ffd6e0 0, transparent 38%),
            radial-gradient(circle at 90% 30%, #f9d9ff 0, transparent 34%),
            radial-gradient(circle at 20% 95%, #ffe1cf 0, transparent 40%),
            linear-gradient(180deg, #fff0f4 0%, #ffe6ee 100%) !important;
        background-attachment: fixed !important;
    }

    .ad-wrap {
        --ad-card: #fffafb; --ad-rose: #f4a7b9; --ad-deep: #d9687f; --ad-ink: #6b2a3f; --ad-line: #f7c8d4;
        position: relative; z-index: 2; max-width: 980px; margin: 0 auto; padding: 8px 4px 24px;
        font-family: 'DM Sans', sans-serif; color: var(--ad-ink);
    }
    .ad-wrap * { box-sizing: border-box; }

    /* Hujan kelopak (hiasan) */
    .ad-hujan { position: fixed; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
    .ad-kelopak { position: absolute; top: -40px; opacity: 0; animation: ad-jatuh linear infinite; }
    @keyframes ad-jatuh {
        0%   { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
        8%   { opacity: .85; }
        50%  { transform: translateY(55vh) translateX(34px) rotate(200deg); }
        100% { transform: translateY(112vh) translateX(-18px) rotate(400deg); opacity: 0; }
    }

    /* Kartu sapaan */
    .ad-hero {
        position: relative; margin-bottom: 30px; padding: 28px 28px 26px;
        background: var(--ad-card); border: 2px dashed var(--ad-rose); border-radius: 26px;
        box-shadow: 0 6px 0 var(--ad-line);
    }
    .ad-hero-bunga {
        position: absolute; top: -18px; right: 18px; width: 62px; height: 62px;
        animation: ad-putar 30s linear infinite;
    }
    @keyframes ad-putar { to { transform: rotate(360deg); } }
    .ad-badge {
        display: inline-block; margin-bottom: 14px; padding: 4px 16px; border-radius: 999px;
        background: rgba(255, 255, 255, .8); border: 2px dashed var(--ad-rose);
        font-size: .82rem; font-weight: 700;
    }
    .ad-hero h1 {
        margin: 0 0 8px; font-family: 'Fraunces', serif; font-weight: 600;
        font-size: 2.2rem; letter-spacing: -.7px; color: var(--ad-ink);
    }
    .ad-hero p { margin: 0; font-size: 1rem; line-height: 1.6; opacity: .88; }
    .ad-hero code {
        padding: 2px 8px; border-radius: 8px; background: rgba(244, 167, 185, .28);
        color: var(--ad-ink); font-size: .9em;
    }

    /* Kartu menu */
    .ad-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
    .ad-kartu {
        position: relative; display: flex; flex-direction: column; padding: 24px 22px 18px;
        border: 2px solid; border-radius: 24px; color: var(--ad-ink); text-decoration: none;
        box-shadow: 0 6px 0 var(--ad-line); transition: transform .15s, box-shadow .15s;
    }
    .ad-kartu:nth-child(odd)  { transform: rotate(-.6deg); }
    .ad-kartu:nth-child(even) { transform: rotate(.6deg); }
    .ad-kartu:hover { transform: translateY(-5px) rotate(0deg); box-shadow: 0 11px 0 var(--ad-line); color: var(--ad-ink); }
    .ad-kartu-bunga { position: absolute; top: -16px; right: -10px; width: 52px; height: 52px; transform: rotate(14deg); }
    .ad-kartu h2 {
        margin: 0 40px 8px 0; font-family: 'Fraunces', serif; font-weight: 600;
        font-size: 1.3rem; line-height: 1.2; letter-spacing: -.3px;
    }
    .ad-kartu p { margin: 0 0 16px; font-size: .92rem; line-height: 1.5; opacity: .88; flex: 1; }
    .ad-bawah { display: flex; justify-content: flex-end; }
    .ad-panah { font-weight: 700; font-size: 1.2rem; transition: transform .15s; }
    .ad-kartu:hover .ad-panah { transform: translateX(5px); }
    .ad-kartu:focus-visible { outline: 3px solid var(--ad-ink); outline-offset: 3px; }

    @media (max-width: 520px) { .ad-hero h1 { font-size: 1.7rem; } }
    @media (prefers-reduced-motion: reduce) {
        .ad-kelopak, .ad-hero-bunga { animation: none; }
        .ad-hujan { display: none; }
        .ad-kartu, .ad-kartu:nth-child(odd), .ad-kartu:nth-child(even) { transform: none; transition: none; }
    }

    /* ===== Mode gelap ===== */
    html[data-bs-theme="dark"] body {
        background-color: #1b1016 !important;
        background-image:
            radial-gradient(circle at 12% 8%, rgba(240, 143, 171, .14) 0, transparent 40%),
            radial-gradient(circle at 90% 30%, rgba(167, 139, 250, .12) 0, transparent 36%),
            radial-gradient(circle at 20% 95%, rgba(251, 146, 60, .08) 0, transparent 40%),
            linear-gradient(180deg, #1d1117 0%, #150c11 100%) !important;
    }
    html[data-bs-theme="dark"] .ad-wrap { --ad-card: #26151e; --ad-rose: #8f4560; --ad-deep: #f08fab; --ad-ink: #ffe3ec; --ad-line: #46283a; }
    html[data-bs-theme="dark"] .ad-badge { background: rgba(0, 0, 0, .28); }
    html[data-bs-theme="dark"] .ad-hero code { background: rgba(240, 143, 171, .18); }
    html[data-bs-theme="dark"] .ad-kartu:nth-child(4n+1) { background: #3a1f2b !important; border-color: #8f4560 !important; }
    html[data-bs-theme="dark"] .ad-kartu:nth-child(4n+2) { background: #3a2a22 !important; border-color: #a8714f !important; }
    html[data-bs-theme="dark"] .ad-kartu:nth-child(4n+3) { background: #2f2540 !important; border-color: #6b57a0 !important; }
    html[data-bs-theme="dark"] .ad-kartu:nth-child(4n+4) { background: #3a1f33 !important; border-color: #94467a !important; }
</style>

<!-- Definisi gambar bunga sakura & kelopak (dipakai berulang) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <radialGradient id="ad-gradien" cx="50%" cy="85%" r="90%">
            <stop offset="0%" stop-color="#f4a7b9"/>
            <stop offset="100%" stop-color="#ffe3ea"/>
        </radialGradient>

        <g id="ad-kelopak-satu">
            <path d="M0 0 C-12 -8 -13 -26 -4 -31 L0 -27 L4 -31 C13 -26 12 -8 0 0 Z"
                  fill="url(#ad-gradien)" stroke="#f4a7b9" stroke-width="1"/>
        </g>

        <g id="ad-bunga">
            <use href="#ad-kelopak-satu" transform="rotate(0)"/>
            <use href="#ad-kelopak-satu" transform="rotate(72)"/>
            <use href="#ad-kelopak-satu" transform="rotate(144)"/>
            <use href="#ad-kelopak-satu" transform="rotate(216)"/>
            <use href="#ad-kelopak-satu" transform="rotate(288)"/>
            <circle r="3.4" fill="#d9687f"/>
            <g stroke="#d9687f" stroke-width="1" stroke-linecap="round">
                <line x1="0" y1="0" x2="0" y2="-9"/>
                <line x1="0" y1="0" x2="8.5" y2="-2.8"/>
                <line x1="0" y1="0" x2="5.3" y2="7.3"/>
                <line x1="0" y1="0" x2="-5.3" y2="7.3"/>
                <line x1="0" y1="0" x2="-8.5" y2="-2.8"/>
            </g>
            <g fill="#b84f65">
                <circle cx="0" cy="-9" r="1.2"/>
                <circle cx="8.5" cy="-2.8" r="1.2"/>
                <circle cx="5.3" cy="7.3" r="1.2"/>
                <circle cx="-5.3" cy="7.3" r="1.2"/>
                <circle cx="-8.5" cy="-2.8" r="1.2"/>
            </g>
        </g>
    </defs>
</svg>

<!-- Kelopak jatuh -->
<div class="ad-hujan" aria-hidden="true">
    @foreach ($kelopak as $k)
        <svg class="ad-kelopak" viewBox="-14 -32 28 34"
             style="left:{{ $k[0] }}%;width:{{ $k[1] }}px;height:{{ $k[1] }}px;animation-delay:{{ $k[2] }}s;animation-duration:{{ $k[3] }}s;">
            <use href="#ad-kelopak-satu"/>
        </svg>
    @endforeach
</div>

<div class="ad-wrap">

    <div class="ad-hero">
        <svg class="ad-hero-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ad-bunga"/></svg>
        <span class="ad-badge">Area Admin</span>
        <h1>Halo, {{ $user->username }}</h1>
        <p>Halaman ini hanya bisa diakses role <code>admin</code> (middleware <code>admin</code>).</p>
    </div>

    <div class="ad-grid">
        <a href="{{ route('admin.roles.index') }}" class="ad-kartu" style="background:#ffe3ea;border-color:#f4a7b9;">
            <svg class="ad-kartu-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ad-bunga"/></svg>
            <h2>Manage Role</h2>
            <p>Tambah role baru untuk dipakai saat membuat user.</p>
            <div class="ad-bawah"><span class="ad-panah" aria-hidden="true">&rarr;</span></div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="ad-kartu" style="background:#fff0e6;border-color:#f8c3a6;">
            <svg class="ad-kartu-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ad-bunga"/></svg>
            <h2>Manage User</h2>
            <p>Tambah user baru dan tentukan role-nya.</p>
            <div class="ad-bawah"><span class="ad-panah" aria-hidden="true">&rarr;</span></div>
        </a>

        <a href="{{ route('admin.database.export') }}" class="ad-kartu" style="background:#f7eaff;border-color:#d9bdf0;">
            <svg class="ad-kartu-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ad-bunga"/></svg>
            <h2>Download Database</h2>
            <p>Unduh seluruh isi database jadi satu file .sql, siap diimpor di server.</p>
            <div class="ad-bawah"><span class="ad-panah" aria-hidden="true">&rarr;</span></div>
        </a>
    </div>

</div>

@endsection