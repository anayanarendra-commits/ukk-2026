@extends('layouts.app')

@section('title', config('app.name') . ' -- Satu Buku, Banyak Cerita')

@section('content')

<style>
    :root {
        --pink-50:  #fff5f8;
        --pink-100: #ffe4ec;
        --pink-200: #ffc9dc;
        --pink-300: #ffa6c8;
        --pink-400: #ff85b3;
        --pink-500: #f4679a;
        --pink-600: #e14f85;
        --pink-ink: #6b3b4d;
        --pink-sub: #a56b81;
        --card-bg: #ffffff;
        --card-border: var(--pink-100);
    }

    /* ---- Dark mode: ngikutin sistem, ATAU toggle situs (data-bs-theme / data-theme / class .dark) ---- */
    @media (prefers-color-scheme: dark) {
        :root {
            --pink-50:  #2a1b22;
            --pink-100: #3a2530;
            --pink-200: #4d2f3d;
            --pink-300: #6b3b52;
            --pink-400: #a3496f;
            --pink-500: #e0688f;
            --pink-600: #f088a8;
            --pink-ink: #ffd9e6;
            --pink-sub: #d9a6bb;
            --card-bg: #201319;
            --card-border: #3a2530;
        }
    }
    html[data-bs-theme="dark"], html[data-theme="dark"], body.dark, body.dark-mode {
        --pink-50:  #2a1b22;
        --pink-100: #3a2530;
        --pink-200: #4d2f3d;
        --pink-300: #6b3b52;
        --pink-400: #a3496f;
        --pink-500: #e0688f;
        --pink-600: #f088a8;
        --pink-ink: #ffd9e6;
        --pink-sub: #d9a6bb;
        --card-bg: #201319;
        --card-border: #3a2530;
    }

    /* ---- Full-bleed wrapper: keluar dari container biar mepet layar ---- */
    .full-bleed {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }

    .sakura-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(160deg, var(--pink-50) 0%, var(--pink-100) 55%, var(--pink-200) 100%);
        padding: 5rem 1.5rem;
        text-align: center;
        min-height: 78vh;
        display: flex;
        align-items: center;
        justify-content: center;
        isolation: isolate;
        transition: background 0.3s ease;
    }

    .petal {
        position: absolute;
        top: -10%;
        width: 18px;
        height: 18px;
        background: var(--pink-300);
        border-radius: 0% 60% 0% 60%;
        opacity: 0.85;
        animation: fall linear infinite;
        z-index: 0;
    }
    .petal:nth-child(1)  { left: 4%;  width: 14px; height: 14px; background: var(--pink-200); animation-duration: 9s;  animation-delay: 0s; }
    .petal:nth-child(2)  { left: 14%; width: 20px; height: 20px; background: var(--pink-400); animation-duration: 11s; animation-delay: 1s; }
    .petal:nth-child(3)  { left: 24%; width: 12px; height: 12px; background: var(--pink-300); animation-duration: 7s;  animation-delay: 2s; }
    .petal:nth-child(4)  { left: 36%; width: 18px; height: 18px; background: var(--pink-200); animation-duration: 10s; animation-delay: 0.5s; }
    .petal:nth-child(5)  { left: 48%; width: 16px; height: 16px; background: var(--pink-400); animation-duration: 8s;  animation-delay: 3s; }
    .petal:nth-child(6)  { left: 60%; width: 22px; height: 22px; background: var(--pink-300); animation-duration: 12s; animation-delay: 1.5s; }
    .petal:nth-child(7)  { left: 72%; width: 14px; height: 14px; background: var(--pink-200); animation-duration: 9.5s; animation-delay: 2.5s; }
    .petal:nth-child(8)  { left: 84%; width: 20px; height: 20px; background: var(--pink-400); animation-duration: 11.5s; animation-delay: 0.8s; }
    .petal:nth-child(9)  { left: 92%; width: 12px; height: 12px; background: var(--pink-300); animation-duration: 8.5s; animation-delay: 3.5s; }
    .petal:nth-child(10) { left: 50%; width: 16px; height: 16px; background: var(--pink-200); animation-duration: 10.5s; animation-delay: 4s; }

    @keyframes fall {
        0%   { transform: translateY(-10%) translateX(0) rotate(0deg);   opacity: 0.9; }
        50%  { transform: translateY(55vh) translateX(30px) rotate(180deg); opacity: 0.7; }
        100% { transform: translateY(120vh) translateX(-20px) rotate(360deg); opacity: 0; }
    }

    .sakura-hero .inner { position: relative; z-index: 2; max-width: 700px; }

    .sakura-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--card-bg);
        color: var(--pink-600);
        border: 1px solid var(--card-border);
        padding: 0.4rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 14px rgba(244, 103, 154, 0.15);
    }

    .sakura-title {
        font-weight: 800;
        font-size: clamp(2.3rem, 6vw, 3.6rem);
        color: var(--pink-ink);
        margin-bottom: 1rem;
        line-height: 1.15;
    }

    .sakura-title .accent {
        background: linear-gradient(90deg, var(--pink-500), var(--pink-600));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .sakura-subtitle {
        color: var(--pink-sub);
        max-width: 560px;
        margin: 0 auto 2.5rem;
        font-size: 1.05rem;
    }

    .btn-sakura {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--pink-500), var(--pink-600));
        color: white !important;
        font-weight: 600;
        padding: 0.75rem 1.75rem;
        border-radius: 999px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(225, 79, 133, 0.3);
        transition: transform 0.15s ease;
    }
    .btn-sakura:hover { transform: translateY(-2px); color: white !important; }

    /* ---- Dua kartu besar: Kategori & Data Buku ---- */
    .aksi-section {
        padding: 4rem 1.5rem 5rem;
    }

    .aksi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }
    @media (max-width: 640px) {
        .aksi-grid { grid-template-columns: 1fr; }
    }

    .aksi-card {
        display: block;
        text-decoration: none;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .aksi-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(225, 79, 133, 0.18);
    }

    .aksi-icon {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--pink-100), var(--pink-200));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.25rem;
    }

    .aksi-card h3 {
        color: var(--pink-ink);
        font-weight: 800;
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }
    .aksi-card p {
        color: var(--pink-sub);
        font-size: 0.92rem;
        margin-bottom: 0;
    }
</style>

{{-- Hero full-bleed --}}
<div class="full-bleed">
    <section class="sakura-hero">
        <span class="petal"></span><span class="petal"></span><span class="petal"></span>
        <span class="petal"></span><span class="petal"></span><span class="petal"></span>
        <span class="petal"></span><span class="petal"></span><span class="petal"></span><span class="petal"></span>

        <div class="inner">
            <span class="sakura-badge">🌸 Perpustakaan Online Sakura</span>

            <h1 class="sakura-title">
                Satu Buku,<br class="d-none d-md-inline">
                <span class="accent">Banyak Cerita</span>
            </h1>

            <p class="sakura-subtitle">
                Jelajahi kategori favoritmu dan temukan buku yang siap menemani
                harimu -- semudah menyentuh layar.
            </p>

            <a href="{{ route('login') }}" class="btn-sakura">🌸 Mulai Sekarang</a>
        </div>
    </section>
</div>

{{-- Dua akses utama: Kategori & Data Buku --}}
<section class="aksi-section">
    <div class="aksi-grid">
        <a href="{{ route('kategori.index') }}" class="aksi-card">
            <div class="aksi-icon">🗂️</div>
            <h3>Kategori</h3>
            <p>Jelajahi buku berdasarkan kategori favoritmu.</p>
        </a>

        <a href="{{ route('data_buku.index') }}" class="aksi-card">
            <div class="aksi-icon">📚</div>
            <h3>Data Buku</h3>
            <p>Lihat semua koleksi buku yang tersedia untuk dipinjam.</p>
        </a>
    </div>
</section>

@endsectionextends('layouts.app')

@section('title', config('app.name') . ' -- Satu Buku, Banyak Cerita')

@section('content')

<style>
    :root {
        --pink-50:  #fff5f8;
        --pink-100: #ffe4ec;
        --pink-200: #ffc9dc;
        --pink-300: #ffa6c8;
        --pink-400: #ff85b3;
        --pink-500: #f4679a;
        --pink-600: #e14f85;
        --pink-ink: #6b3b4d;
    }

    .sakura-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background: linear-gradient(160deg, var(--pink-50) 0%, var(--pink-100) 55%, var(--pink-200) 100%);
        padding: 4rem 1.5rem;
        text-align: center;
        margin-bottom: 3rem;
        isolation: isolate;
    }

    .petal {
        position: absolute;
        top: -10%;
        width: 18px;
        height: 18px;
        background: var(--pink-300);
        border-radius: 0% 60% 0% 60%;
        opacity: 0.85;
        animation: fall linear infinite;
        z-index: 0;
    }
    .petal:nth-child(1)  { left: 4%;  width: 14px; height: 14px; background: var(--pink-200); animation-duration: 9s;  animation-delay: 0s; }
    .petal:nth-child(2)  { left: 14%; width: 20px; height: 20px; background: var(--pink-400); animation-duration: 11s; animation-delay: 1s; }
    .petal:nth-child(3)  { left: 24%; width: 12px; height: 12px; background: var(--pink-300); animation-duration: 7s;  animation-delay: 2s; }
    .petal:nth-child(4)  { left: 36%; width: 18px; height: 18px; background: var(--pink-200); animation-duration: 10s; animation-delay: 0.5s; }
    .petal:nth-child(5)  { left: 48%; width: 16px; height: 16px; background: var(--pink-400); animation-duration: 8s;  animation-delay: 3s; }
    .petal:nth-child(6)  { left: 60%; width: 22px; height: 22px; background: var(--pink-300); animation-duration: 12s; animation-delay: 1.5s; }
    .petal:nth-child(7)  { left: 72%; width: 14px; height: 14px; background: var(--pink-200); animation-duration: 9.5s; animation-delay: 2.5s; }
    .petal:nth-child(8)  { left: 84%; width: 20px; height: 20px; background: var(--pink-400); animation-duration: 11.5s; animation-delay: 0.8s; }
    .petal:nth-child(9)  { left: 92%; width: 12px; height: 12px; background: var(--pink-300); animation-duration: 8.5s; animation-delay: 3.5s; }
    .petal:nth-child(10) { left: 50%; width: 16px; height: 16px; background: var(--pink-200); animation-duration: 10.5s; animation-delay: 4s; }

    @keyframes fall {
        0%   { transform: translateY(-10%) translateX(0) rotate(0deg);   opacity: 0.9; }
        50%  { transform: translateY(55vh) translateX(30px) rotate(180deg); opacity: 0.7; }
        100% { transform: translateY(120vh) translateX(-20px) rotate(360deg); opacity: 0; }
    }

    .sakura-hero .inner { position: relative; z-index: 2; }

    .sakura-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: white;
        color: var(--pink-600);
        border: 1px solid var(--pink-200);
        padding: 0.4rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 14px rgba(244, 103, 154, 0.15);
    }

    .sakura-title {
        font-weight: 800;
        font-size: clamp(2.1rem, 5vw, 3.2rem);
        color: var(--pink-ink);
        margin-bottom: 1rem;
        line-height: 1.15;
    }

    .sakura-title .accent {
        background: linear-gradient(90deg, var(--pink-500), var(--pink-600));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .sakura-subtitle {
        color: #a56b81;
        max-width: 560px;
        margin: 0 auto 2rem;
        font-size: 1.05rem;
    }

    .sakura-search {
        max-width: 520px;
        margin: 0 auto 1.75rem;
        display: flex;
        gap: 0.5rem;
        background: white;
        padding: 0.4rem;
        border-radius: 999px;
        box-shadow: 0 10px 30px rgba(225, 79, 133, 0.15);
        border: 1px solid var(--pink-200);
    }
    .sakura-search input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        color: var(--pink-ink);
    }
    .sakura-search input::placeholder { color: #d99cb3; }
    .sakura-search button {
        border: none;
        background: linear-gradient(135deg, var(--pink-500), var(--pink-600));
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.4rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .btn-sakura {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--pink-500), var(--pink-600));
        color: white !important;
        font-weight: 600;
        padding: 0.75rem 1.75rem;
        border-radius: 999px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(225, 79, 133, 0.3);
        transition: transform 0.15s ease;
    }
    .btn-sakura:hover { transform: translateY(-2px); }

    .btn-sakura-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: var(--pink-600) !important;
        font-weight: 600;
        padding: 0.75rem 1.75rem;
        border-radius: 999px;
        text-decoration: none;
        border: 1.5px solid var(--pink-300);
    }

    .sakura-stats {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2.5rem;
        margin-top: 2.5rem;
    }
    .sakura-stats .stat-num {
        font-weight: 800;
        font-size: 1.6rem;
        color: var(--pink-600);
    }
    .sakura-stats .stat-label {
        font-size: 0.85rem;
        color: #b17c92;
    }

    .sakura-card {
        border: 1px solid var(--pink-100);
        border-radius: 20px;
        background: white;
        padding: 1.75rem;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .sakura-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(225, 79, 133, 0.15);
    }
    .sakura-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--pink-100), var(--pink-200));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }
    .sakura-card h3 { color: var(--pink-ink); font-weight: 700; font-size: 1.1rem; }
    .sakura-card p { color: #a56b81; font-size: 0.92rem; margin-bottom: 0; }

    .section-heading {
        text-align: center;
        max-width: 560px;
        margin: 0 auto 2.5rem;
    }
    .section-heading .eyebrow {
        color: var(--pink-500);
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .section-heading h2 {
        color: var(--pink-ink);
        font-weight: 800;
        margin-top: 0.4rem;
    }

    .kategori-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--pink-50);
        border: 1px solid var(--pink-200);
        color: var(--pink-ink);
        padding: 0.6rem 1.2rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        margin: 0.3rem;
        transition: background 0.2s ease;
    }
    .kategori-pill:hover { background: var(--pink-200); }

    .sakura-cta {
        border-radius: 28px;
        background: linear-gradient(135deg, var(--pink-500), var(--pink-600));
        color: white;
        text-align: center;
        padding: 3rem 1.5rem;
        margin: 3rem 0;
        position: relative;
        overflow: hidden;
    }
    .sakura-cta::before, .sakura-cta::after {
        content: "🌸";
        position: absolute;
        font-size: 3rem;
        opacity: 0.15;
    }
    .sakura-cta::before { top: -1rem; left: -1rem; }
    .sakura-cta::after { bottom: -1rem; right: -1rem; }
    .sakura-cta h2 { font-weight: 800; margin-bottom: 0.75rem; }
    .sakura-cta p { opacity: 0.9; max-width: 480px; margin: 0 auto 1.75rem; }
    .sakura-cta .btn-sakura-outline {
        background: white;
        color: var(--pink-600) !important;
        border: none;
    }
</style>

{{-- Hero --}}
<section class="sakura-hero">
    <span class="petal"></span><span class="petal"></span><span class="petal"></span>
    <span class="petal"></span><span class="petal"></span><span class="petal"></span>
    <span class="petal"></span><span class="petal"></span><span class="petal"></span><span class="petal"></span>

    <div class="inner">
        <span class="sakura-badge">🌸 Perpustakaan Online Sakura</span>

        <h1 class="sakura-title">
            Satu Buku,<br class="d-none d-md-inline">
            <span class="accent">Banyak Cerita</span>
        </h1>

        <p class="sakura-subtitle">
            Jelajahi ribuan koleksi buku, pinjam kapan saja, dan biarkan setiap
            halaman membawamu ke dunia baru -- semudah menyentuh layar.
        </p>

        <form class="sakura-search" action="{{ route('login') }}" method="get">
            <input type="text" placeholder="Cari judul, penulis, atau kategori favoritmu...">
            <button type="submit">Cari</button>
        </form>

        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ route('register') }}" class="btn-sakura">🌸 Mulai Meminjam</a>
            <a href="{{ route('docs') }}" class="btn-sakura-outline">Lihat Panduan</a>
        </div>
        </div>
    </div>
</section>

{{-- Kenapa pilih kami --}}
<section class="mb-5">
    <div class="section-heading">
        <span class="eyebrow">Kenapa Sakura</span>
        <h2>Membaca jadi lebih mudah</h2>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="sakura-card">
                <div class="sakura-icon">📖</div>
                <h3>Koleksi Lengkap</h3>
                <p>Ratusan kategori dari fiksi, sains, hingga buku anak, selalu diperbarui setiap bulan.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sakura-card">
                <div class="sakura-icon">⏱️</div>
                <h3>Pinjam Cepat</h3>
                <p>Cukup beberapa klik untuk meminjam buku, tanpa antre dan tanpa ribet.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sakura-card">
                <div class="sakura-icon">🌷</div>
                <h3>Tampilan Nyaman</h3>
                <p>Desain lembut dan menenangkan, dibuat agar betah berlama-lama menjelajah rak buku.</p>
            </div>
        </div>
    </div>
</section>

{{-- Kategori populer --}}
<section class="mb-5">
    <div class="section-heading">
        <span class="eyebrow">Jelajahi</span>
        <h2>Kategori Populer</h2>
    </div>

    <div class="text-center">
        <a href="#" class="kategori-pill">💌 Romansa</a>
        <a href="#" class="kategori-pill">🕵️ Misteri</a>
        <a href="#" class="kategori-pill">🌌 Fiksi Ilmiah</a>
        <a href="#" class="kategori-pill">📚 Sejarah</a>
        <a href="#" class="kategori-pill">🧠 Pengembangan Diri</a>
        <a href="#" class="kategori-pill">🧸 Anak & Remaja</a>
        <a href="#" class="kategori-pill">🎨 Seni & Budaya</a>
    </div>
</section>

{{-- CTA --}}
<section class="sakura-cta">
    <h2>Siap memulai cerita barumu?</h2>
    <p>Daftar sekarang dan temukan buku pertamamu di Perpustakaan Sakura hari ini juga.</p>
    <a href="{{ route('register') }}" class="btn-sakura-outline">🌸 Daftar Gratis</a>
</section>

@endsection