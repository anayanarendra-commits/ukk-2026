<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;1,500&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --pk-bg: #fff0f3;
        --pk-card: #fffafb;
        --pk-soft: #ffd6e0;
        --pk-rose: #f4a7b9;
        --pk-deep: #d9687f;
        --pk-ink: #6b2a3f;
        --pk-line: #f7c8d4;
        --sb-lebar: 270px;
    }

    .sb, .sb *, .sb-buka { box-sizing: border-box; }

    /* Tombol pembuka: satu-satunya elemen yang terlihat saat halaman pertama dibuka */
    .sb-buka {
        position: fixed; top: 1rem; left: 1rem; z-index: 1030;
        width: 46px; height: 46px; display: flex; align-items: center; justify-content: center;
        background: var(--pk-card); color: var(--pk-ink);
        border: 2px solid var(--pk-rose); border-radius: 14px; cursor: pointer;
        box-shadow: 0 3px 0 var(--pk-line); transition: transform .1s, box-shadow .1s, background .15s;
    }
    .sb-buka:hover { background: var(--pk-soft); transform: translateY(1px); box-shadow: 0 2px 0 var(--pk-line); }

    /* Sidebar: tersembunyi sampai tombol ditekan */
    .sb {
        position: fixed; top: 0; left: 0; bottom: 0; width: var(--sb-lebar); max-width: 85vw; z-index: 1040;
        display: flex; flex-direction: column; overflow-y: auto;
        background: var(--pk-card); border-right: 2px dashed var(--pk-rose);
        padding: 1.25rem 1rem; font-family: 'DM Sans', sans-serif; color: var(--pk-ink);
        transform: translateX(-100%); visibility: hidden;
        transition: transform .25s, visibility 0s .25s;
    }
    .sb.terbuka {
        transform: none; visibility: visible; transition: transform .25s;
        box-shadow: 6px 0 24px rgba(107, 42, 63, .15);
    }
    .sb-overlay { display: none; position: fixed; inset: 0; z-index: 1035; background: rgba(107, 42, 63, .35); }
    .sb-overlay.terbuka { display: block; }

    .sb-brand { display: flex; align-items: center; gap: .65rem; padding: .25rem .5rem 1.4rem; }
    .sb-brand a {
        font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.3rem; line-height: 1.1;
        color: var(--pk-ink); text-decoration: none; letter-spacing: -.3px;
    }
    /* Tombol tema berbentuk bunga sakura. Titik tengah = status database (hijau/merah). */
    .sb .logo-toggle { background: none; border: 0; padding: 0; cursor: pointer; line-height: 0; flex: none; border-radius: 50%; }
    .sb .flower { transition: transform .5s ease; }
    .sb .logo-toggle:hover .flower { transform: rotate(36deg) scale(1.1); }
    .sb .petal { fill: #ffc9d8; stroke: var(--pk-deep); stroke-width: 1; stroke-linejoin: round; transition: fill .3s, stroke .3s; }
    .sb .flower-pusat { stroke: var(--pk-card); stroke-width: 1.6; }
    .sb .logo-toggle:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 3px; }
    .sb-tutup {
        margin-left: auto; background: none; border: 2px solid var(--pk-line); border-radius: 10px;
        padding: .25rem; line-height: 0; cursor: pointer; color: var(--pk-ink);
    }
    .sb-tutup:hover { background: var(--pk-soft); }

    .sb-menu { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .35rem; }
    .sb-link {
        display: flex; align-items: center; gap: .7rem; padding: .6rem .9rem;
        border-radius: 10px; border-left: 6px solid transparent;
        color: var(--pk-ink); text-decoration: none; font-weight: 500; transition: background .15s;
    }
    .sb-link:hover { background: var(--pk-soft); color: var(--pk-ink); }
    .sb-link.aktif { background: var(--pk-soft); border-left-color: var(--pk-deep); font-weight: 700; }
    .sb-link svg { width: 18px; height: 18px; flex: none; }
    .sb-info { margin: 0; padding: .25rem .5rem; color: var(--pk-deep); font-size: .9rem; }
    .sb-link:focus-visible, .sb-btn:focus-visible, .sb-btn-pink:focus-visible, .sb-buka:focus-visible, .sb-tutup:focus-visible {
        outline: 3px solid var(--pk-ink); outline-offset: 2px;
    }

    .sb-bawah { margin-top: auto; padding-top: 1rem; border-top: 2px dashed var(--pk-line); display: flex; flex-direction: column; gap: .6rem; }
    .sb-bawah form { margin: 0; }
    .sb-btn, .sb-btn-pink {
        display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%;
        font-family: inherit; font-size: .9rem; font-weight: 700; cursor: pointer; text-decoration: none;
        border-radius: 999px; padding: .55rem 1rem;
    }
    .sb-btn { background: transparent; border: 2px solid var(--pk-rose); color: var(--pk-ink); transition: background .15s; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sb-btn:hover { background: var(--pk-soft); }
    .sb-btn-pink { background: var(--pk-deep); border: 0; color: #fff; box-shadow: 0 4px 0 #b84f65; transition: transform .1s, box-shadow .1s; }
    .sb-btn-pink:hover { color: #fff; transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }

    /* ===== Mode gelap: menu ikut gelap, bunga berputar dan berubah warna ===== */
    [data-bs-theme="dark"] .sb,
    [data-bs-theme="dark"] .sb-buka,
    [data-bs-theme="dark"] .sb-overlay {
        --pk-card: #24141c; --pk-soft: #3b2230; --pk-rose: #8f4560;
        --pk-deep: #f08fab; --pk-ink: #ffe3ec; --pk-line: #46283a;
    }
    [data-bs-theme="dark"] .sb.terbuka { box-shadow: 6px 0 24px rgba(0, 0, 0, .45); }
    [data-bs-theme="dark"] .sb-overlay { background: rgba(0, 0, 0, .55); }
    [data-bs-theme="dark"] .sb-btn-pink { color: #3a1523; box-shadow: 0 4px 0 #b9627f; }
    [data-bs-theme="dark"] .sb-btn-pink:hover { color: #3a1523; box-shadow: 0 2px 0 #b9627f; }
    [data-bs-theme="dark"] .sb .flower { transform: rotate(36deg); }
    [data-bs-theme="dark"] .sb .logo-toggle:hover .flower { transform: rotate(72deg) scale(1.1); }
    [data-bs-theme="dark"] .sb .petal { fill: #5a2e41; stroke: #f08fab; }

    /* ===== Mode gelap untuk seluruh halaman admin =====
       Semua halaman memakai variabel --pk-*, jadi cukup ganti nilainya di sini. */
    html[data-bs-theme="dark"],
    html[data-bs-theme="dark"] body,
    body[data-bs-theme="dark"] {
        --pk-bg: #1b0f15; --pk-card: #24141c; --pk-soft: #3b2230; --pk-rose: #8f4560;
        --pk-deep: #f08fab; --pk-ink: #ffe3ec; --pk-line: #46283a;
        color-scheme: dark;
    }
    /* Bagian yang warnanya putih/terang ditulis langsung di halaman */
    [data-bs-theme="dark"] .notif.gagal,
    [data-bs-theme="dark"] .lencana.menunggu { background: var(--pk-card); }
    [data-bs-theme="dark"] .btn-pink { color: #3a1523; box-shadow: 0 4px 0 #b9627f; }
    [data-bs-theme="dark"] .btn-pink:hover { color: #3a1523; box-shadow: 0 2px 0 #b9627f; }
    [data-bs-theme="dark"] .lencana.telat,
    [data-bs-theme="dark"] .hitung,
    [data-bs-theme="dark"] .aksi-btn.setujui { color: #3a1523; }
    [data-bs-theme="dark"] .aksi-btn.setujui:hover { background: #ffb3c8; border-color: #ffb3c8; color: #3a1523; }
    [data-bs-theme="dark"] .aksi-btn.kembali { color: #ffe3ec; }
    [data-bs-theme="dark"] .aksi-btn.kembali:hover,
    [data-bs-theme="dark"] .aksi-btn.hapus:hover { color: #3a1523; }
    [data-bs-theme="dark"] .katalog .pagination li.active > span,
    [data-bs-theme="dark"] .katalog .pagination li.active > a { color: #3a1523; }
    [data-bs-theme="dark"] .blok-pengajuan { background: rgba(240, 143, 171, .08); }

    /* ===== Lapisan mode gelap umum (semua halaman admin) =====
       Menutup bagian yang tidak memakai variabel --pk-*: kartu, tabel, form, footer, dll. */
    html[data-bs-theme="dark"] {
        --bs-body-bg: #1b0f15; --bs-body-bg-rgb: 27, 15, 21;
        --bs-body-color: #ffe3ec; --bs-body-color-rgb: 255, 227, 236;
        --bs-emphasis-color: #fff4f8;
        --bs-secondary-color: rgba(255, 227, 236, .7);
        --bs-secondary-bg: #2f1b26; --bs-tertiary-bg: #24141c;
        --bs-border-color: #46283a;
        --bs-link-color: #ff9dba; --bs-link-hover-color: #ffc2d5;
    }
    [data-bs-theme="dark"] body { background-color: #1b0f15; color: #ffe3ec; }

    [data-bs-theme="dark"] .card,
    [data-bs-theme="dark"] .modal-content,
    [data-bs-theme="dark"] .dropdown-menu,
    [data-bs-theme="dark"] .list-group-item,
    [data-bs-theme="dark"] .offcanvas,
    [data-bs-theme="dark"] .accordion-item,
    [data-bs-theme="dark"] .toast { background-color: #24141c; color: #ffe3ec; border-color: #46283a; }
    [data-bs-theme="dark"] .card-header,
    [data-bs-theme="dark"] .card-footer,
    [data-bs-theme="dark"] .modal-header,
    [data-bs-theme="dark"] .modal-footer { background-color: #2f1b26; border-color: #46283a; color: #ffe3ec; }

    [data-bs-theme="dark"] .table {
        --bs-table-color: #ffe3ec; --bs-table-bg: transparent; --bs-table-border-color: #46283a;
        --bs-table-striped-bg: rgba(255, 255, 255, .04); --bs-table-striped-color: #ffe3ec;
        --bs-table-hover-bg: rgba(240, 143, 171, .08); --bs-table-hover-color: #ffe3ec;
        color: #ffe3ec; border-color: #46283a;
    }
    [data-bs-theme="dark"] .table > thead > tr > th { background-color: #3b2230; color: #ffe3ec; }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select,
    [data-bs-theme="dark"] .input-group-text,
    [data-bs-theme="dark"] textarea,
    [data-bs-theme="dark"] select,
    [data-bs-theme="dark"] input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="range"]):not([type="color"]) {
        background-color: #1b0f15; color: #ffe3ec; border-color: #5a3447;
    }
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #1b0f15; color: #ffe3ec; border-color: #f08fab;
        box-shadow: 0 0 0 .25rem rgba(240, 143, 171, .25);
    }
    [data-bs-theme="dark"] ::placeholder { color: #a9788b; opacity: 1; }
    [data-bs-theme="dark"] label,
    [data-bs-theme="dark"] .form-label { color: #ffe3ec; }
    [data-bs-theme="dark"] .form-text,
    [data-bs-theme="dark"] .text-muted,
    [data-bs-theme="dark"] .text-secondary { color: #c79aac !important; }
    [data-bs-theme="dark"] .text-dark { color: #ffe3ec !important; }
    [data-bs-theme="dark"] .bg-white,
    [data-bs-theme="dark"] .bg-light { background-color: #24141c !important; }
    [data-bs-theme="dark"] .border,
    [data-bs-theme="dark"] .border-top,
    [data-bs-theme="dark"] .border-bottom { border-color: #46283a !important; }
    [data-bs-theme="dark"] hr { border-color: #46283a; opacity: 1; }
    [data-bs-theme="dark"] .btn-close { filter: invert(1); }

    [data-bs-theme="dark"] .page-link { background-color: #24141c; border-color: #46283a; color: #ff9dba; }
    [data-bs-theme="dark"] .page-item.active .page-link { background-color: #f08fab; border-color: #f08fab; color: #3a1523; }

    [data-bs-theme="dark"] .navbar,
    [data-bs-theme="dark"] footer { background-color: #24141c !important; border-color: #46283a !important; color: #c79aac; }

    /* ===== Kolom cari buku (halaman Data Buku) ===== */
    .cari-buku { position: relative; display: flex; align-items: center; gap: .6rem; margin: 0 0 .4rem; font-family: 'DM Sans', sans-serif; }
    .cari-buku svg { position: absolute; left: 1rem; width: 18px; height: 18px; color: var(--pk-deep); pointer-events: none; }
    .cari-buku input {
        flex: 1; min-width: 0; font: inherit; color: var(--pk-ink); background: var(--pk-card);
        border: 2px solid var(--pk-rose); border-radius: 999px; padding: .65rem 1.1rem .65rem 2.8rem;
        outline: none; box-shadow: 0 3px 0 var(--pk-line); transition: border-color .15s, box-shadow .15s;
    }
    .cari-buku input:focus { border-color: var(--pk-deep); box-shadow: 0 0 0 4px rgba(244, 167, 185, .35); }
    .cari-buku input::placeholder { color: var(--pk-deep); opacity: .65; }
    .cari-buku input::-webkit-search-cancel-button { display: none; }
    .cari-buku button {
        display: none; font: inherit; font-weight: 700; font-size: .85rem; cursor: pointer;
        border: 2px solid var(--pk-rose); background: transparent; color: var(--pk-ink);
        border-radius: 999px; padding: .45rem 1.1rem; transition: background .15s;
    }
    .cari-buku button:hover { background: var(--pk-soft); }
    .cari-buku button:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 2px; }
    .cari-info { margin: .4rem 1rem 0; font-family: 'DM Sans', sans-serif; font-size: .85rem; color: var(--pk-deep); min-height: 1.2em; }
    .rak tr.cari-kosong td {
        text-align: center; padding: 2rem 1rem; color: var(--pk-deep); background: var(--pk-card);
        border-left: 0; border-radius: 10px; width: auto; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 400;
    }

    /* Konten ikut bergeser saat menu dibuka (layar lebar).
       Di layar sempit tetap memakai latar gelap seperti sebelumnya. */
    body { transition: margin-left .25s ease; }
    @media (min-width: 900px) {
        body.sb-geser { margin-left: var(--sb-lebar); overflow-x: hidden; }
        .sb-overlay.terbuka { display: none; }
        .sb.terbuka { box-shadow: none; }
    }
    @media (prefers-reduced-motion: reduce) { body { transition: none; } }

    /* Di layar sempit, beri ruang supaya tombol tidak menimpa judul halaman */
    @media (max-width: 700px) { body { padding-top: 56px; } }
    @media (prefers-reduced-motion: reduce) { .sb, .sb.terbuka { transition: none; } }
</style>

@php
    $dbConnected = false;
    try {
        \Sakuci\Database\Connection::pdo();
        $dbConnected = true;
    } catch (\Throwable $e) {
        $dbConnected = false;
    }
    $currentUser = \App\Models\User::current();
@endphp

<button type="button" id="sbBuka" class="sb-buka" aria-label="Buka menu" aria-controls="sbPanel" aria-expanded="false">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>
    </svg>
</button>

<div class="sb-overlay" id="sbOverlay"></div>

<aside class="sb" id="sbPanel" aria-label="Menu samping">
    <div class="sb-brand">
        <button id="themeToggle" type="button" class="logo-toggle"
                aria-label="Ganti tema terang/gelap (status database: {{ $dbConnected ? 'terhubung' : 'tidak terhubung' }})"
                title="Ganti tema terang/gelap">
            <svg width="38" height="38" viewBox="-20 -20 40 40" xmlns="http://www.w3.org/2000/svg" style="display: block;" aria-hidden="true">
                <g class="flower">
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(0)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(72)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(144)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(216)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(288)"/>
                    <circle class="flower-pusat" r="4.6" fill="{{ $dbConnected ? '#28a745' : '#dc3545' }}"/>
                </g>
            </svg>
        </button>
        <a href="{{ route('home') }}">Peminjaman Buku</a>
        <button type="button" id="sbTutup" class="sb-tutup" aria-label="Tutup menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/>
            </svg>
        </button>
    </div>

    <nav aria-label="Menu utama">
        @if ($currentUser)
            <ul class="sb-menu">
            @if ($currentUser->role === 'admin')
                {{-- Menu admin: tidak berubah --}}
                <li>
                    <a class="sb-link {{ is_route('kategori.index') ? 'aktif' : '' }}" href="{{ route('kategori.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2" fill="currentColor"/>
                        </svg>
                        Kategori
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('data_buku.index') ? 'aktif' : '' }}" href="{{ route('data_buku.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>
                        </svg>
                        Data Buku
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('peminjaman.index') ? 'aktif' : '' }}" href="{{ route('peminjaman.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 2l4 4-4 4"/><path d="M3 11V9a3 3 0 0 1 3-3h15"/><path d="M7 22l-4-4 4-4"/><path d="M21 13v2a3 3 0 0 1-3 3H3"/>
                        </svg>
                        Peminjaman
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('pengembalian.index') ? 'aktif' : '' }}" href="{{ route('pengembalian.riwayat') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 14 4 9l5-5"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/>
                        </svg>
                        Pengembalian
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('admin.dashboard', 'dashboard') ? 'aktif' : '' }}" href="{{ route('admin.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
            @else
                {{-- Menu siswa: Dashboard, Kategori, dan Pengembalian Buku --}}
                <li>
                    <a class="sb-link {{ is_route('siswa.dashboard') ? 'aktif' : '' }}" href="{{ route('siswa.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('siswa.kategori', 'siswa.kategori.buku') ? 'aktif' : '' }}" href="{{ route('siswa.kategori') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2" fill="currentColor"/>
                        </svg>
                        Kategori
                    </a>
                </li>
                <li>
                    <a class="sb-link {{ is_route('siswa.peminjaman.index') ? 'aktif' : '' }}" href="{{ route('siswa.peminjaman.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 14 4 9l5-5"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/>
                        </svg>
                        Pengembalian Buku
                    </a>
                </li>
            @endif
            </ul>
        @else
            <p class="sb-info">Masuk dulu untuk melihat menu kategori, data buku, dan peminjaman.</p>
        @endif
    </nav>

    <div class="sb-bawah">
        @if ($currentUser)
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-btn">Logout ({{ $currentUser->username }})</button>
            </form>
        @else
            @php
                $canRegister = false;
                if ($dbConnected) {
                    try {
                        $canRegister = \App\Models\Role::where('can_register', 1)->exists();
                    } catch (\Throwable $e) {
                        $canRegister = false;
                    }
                }
            @endphp
            @if ($canRegister)
                <a class="sb-btn" href="{{ route('register') }}">Daftar</a>
            @endif
            <a class="sb-btn-pink" href="{{ route('login') }}">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="8" cy="5" r="3" fill="currentColor" stroke="none"/>
                    <path d="M2.5 14c0-3.6 2.9-5.8 5.5-5.8s5.5 2.2 5.5 5.8"/>
                </svg>
                Masuk
            </a>
        @endif
    </div>
</aside>

<script>
    (function () {
        var panel = document.getElementById('sbPanel');
        var overlay = document.getElementById('sbOverlay');
        var buka = document.getElementById('sbBuka');
        var tutup = document.getElementById('sbTutup');
        function atur(terbuka) {
            panel.classList.toggle('terbuka', terbuka);
            overlay.classList.toggle('terbuka', terbuka);
            document.body.classList.toggle('sb-geser', terbuka);
            buka.setAttribute('aria-expanded', terbuka ? 'true' : 'false');
            if (terbuka) { tutup.focus(); } else { buka.focus(); }
        }
        buka.addEventListener('click', function () { atur(true); });
        tutup.addEventListener('click', function () { atur(false); });
        overlay.addEventListener('click', function () { atur(false); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && panel.classList.contains('terbuka')) atur(false);
        });
    })();
</script>

<script>
    // Cari buku sambil mengetik: hanya aktif di halaman Data Buku admin
    (function () {
        if (location.pathname.replace(/\/+$/, '') !== '/admin/data_buku') return;

        function mulai() {
            var badan = document.querySelector('.rak tbody');
            var kepala = document.querySelector('.katalog-head');
            if (!badan || !kepala || document.getElementById('cariBuku')) return;

            // Hanya baris data (bukan baris "kosong"); teks kolom Aksi tidak ikut dicari
            var baris = Array.prototype.slice.call(badan.querySelectorAll('tr')).filter(function (r) { return r.cells.length > 1; });
            var teks = baris.map(function (r) {
                var sel = Array.prototype.slice.call(r.cells, 0, r.cells.length - 1);
                return sel.map(function (c) { return c.textContent; }).join(' ').toLowerCase().replace(/\s+/g, ' ');
            });
            var jumlahKolom = baris.length ? baris[0].cells.length : 5;

            var kotak = document.createElement('div');
            kotak.className = 'cari-buku';
            kotak.innerHTML =
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="21" y2="21"/></svg>' +
                '<input type="search" id="cariBuku" placeholder="Cari judul, pengarang, atau penerbit..." autocomplete="off" aria-label="Cari buku">' +
                '<button type="button" id="cariReset">Reset</button>';
            var info = document.createElement('p');
            info.className = 'cari-info';
            info.id = 'cariInfo';
            info.setAttribute('aria-live', 'polite');
            kepala.parentNode.insertBefore(kotak, kepala.nextSibling);
            kotak.parentNode.insertBefore(info, kotak.nextSibling);

            var input = document.getElementById('cariBuku');
            var reset = document.getElementById('cariReset');
            var barisKosong = null;
            var adaHalaman = !!document.querySelector('.pagination li a');

            function cari() {
                var kata = input.value.toLowerCase().replace(/\s+/g, ' ').trim();
                var kunci = kata === '' ? [] : kata.split(' ');
                var tampil = 0;

                baris.forEach(function (r, i) {
                    var cocok = kunci.every(function (k) { return teks[i].indexOf(k) !== -1; });
                    r.style.display = cocok ? '' : 'none';
                    if (cocok) tampil++;
                });

                if (barisKosong) { barisKosong.remove(); barisKosong = null; }
                if (kunci.length && tampil === 0) {
                    barisKosong = document.createElement('tr');
                    barisKosong.className = 'cari-kosong';
                    var td = document.createElement('td');
                    td.colSpan = jumlahKolom;
                    td.textContent = 'Tidak ada buku yang cocok dengan "' + input.value.trim() + '".';
                    barisKosong.appendChild(td);
                    badan.appendChild(barisKosong);
                }

                reset.style.display = kunci.length ? 'inline-block' : 'none';

                if (!kunci.length) {
                    info.textContent = adaHalaman ? 'Mencari di halaman yang sedang dibuka.' : '';
                } else {
                    info.textContent = 'Menampilkan ' + tampil + ' dari ' + baris.length + ' buku'
                        + (adaHalaman ? ' di halaman ini.' : '.');
                }
            }

            input.addEventListener('input', cari);
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { input.value = ''; cari(); }
            });
            reset.addEventListener('click', function () { input.value = ''; cari(); input.focus(); });
            cari();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', mulai);
        } else {
            mulai();
        }
    })();
</script>