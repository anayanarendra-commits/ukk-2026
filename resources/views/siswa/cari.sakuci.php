<?php
// View: siswa/cari (hasil pencarian buku berdasarkan judul)
// Variabel dari controller: $hasil (daftar buku), $kata (kata kunci)

// Kelopak yang jatuh: [posisi kiri %, ukuran px, delay detik, durasi detik]
$kelopak = [
    [4, 20, 0, 14], [14, 12, 4, 17], [24, 16, 8, 15], [36, 10, 2, 18],
    [47, 20, 6, 16], [58, 12, 10, 14], [68, 16, 1, 17], [78, 10, 5, 15],
    [87, 20, 9, 16], [95, 14, 3, 18],
];

// Warna pastel untuk kartu (bergantian): [latar, garis]
$warna = [
    ['#ffe3ea', '#f4a7b9'],
    ['#fff0e6', '#f8c3a6'],
    ['#f7eaff', '#d9bdf0'],
    ['#ffeaf4', '#f2b0d2'],
];
?>
<script>
// Terapkan tema tersimpan lebih awal agar halaman tidak berkedip
(function () {
  try { document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('sakuci-theme') || 'light'); } catch (e) {}
})();
</script>
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

    .kt-wrap {
        --kt-card: #fffafb; --kt-rose: #f4a7b9; --kt-deep: #d9687f; --kt-ink: #6b2a3f; --kt-line: #f7c8d4;
        position: relative; z-index: 2; max-width: 980px; margin: 0 auto; padding: 36px 20px 48px;
        font-family: 'DM Sans', sans-serif; color: var(--kt-ink);
    }
    .kt-wrap * { box-sizing: border-box; }

    /* Hujan kelopak (hiasan, menutupi seluruh layar) */
    .kt-hujan { position: fixed; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
    .kt-kelopak { position: absolute; top: -40px; opacity: 0; animation: kt-jatuh linear infinite; }
    @keyframes kt-jatuh {
        0%   { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
        8%   { opacity: .85; }
        50%  { transform: translateY(55vh) translateX(34px) rotate(200deg); }
        100% { transform: translateY(112vh) translateX(-18px) rotate(400deg); opacity: 0; }
    }

    /* Judul halaman */
    .kt-judul {
        display: flex; align-items: center; justify-content: center; gap: 16px;
        text-align: center; margin-bottom: 8px;
    }
    .kt-judul svg { width: 54px; height: 54px; flex: none; animation: kt-putar 30s linear infinite; }
    @keyframes kt-putar { to { transform: rotate(360deg); } }
    .kt-judul h2 {
        margin: 0; font-family: 'Fraunces', serif; font-weight: 600; font-size: 2.4rem;
        letter-spacing: -.8px; color: var(--kt-ink);
    }
    .kt-sub {
        text-align: center; margin: 0 auto 32px; max-width: 460px; font-size: 1rem; opacity: .85;
    }
    .kt-sub span {
        display: inline-block; padding: 5px 16px; border-radius: 999px;
        background: rgba(255, 250, 251, .8); border: 2px dashed var(--kt-rose);
    }

    /* Kotak pencarian */
    .kt-cari { display: flex; gap: 8px; max-width: 480px; margin: -8px auto 30px; }
    .kt-cari input {
        flex: 1; min-width: 0; padding: 12px 18px; border: 2px solid var(--kt-rose);
        border-radius: 999px; background: var(--kt-card); color: var(--kt-ink);
        font-family: inherit; font-size: 1rem; outline: none;
    }
    .kt-cari input:focus { border-color: var(--kt-deep); }
    .kt-cari button {
        padding: 12px 24px; border: 0; border-radius: 999px; background: var(--kt-deep);
        color: #fff; font-family: inherit; font-size: 1rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 0 #b84f65;
    }
    .kt-cari button:active { transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }
    .kt-cari input:focus-visible, .kt-cari button:focus-visible { outline: 3px solid var(--kt-ink); outline-offset: 2px; }

    /* Kartu kategori */
    .kt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 22px; }
    .kt-kartu {
        position: relative; display: flex; flex-direction: column; padding: 22px 20px 18px;
        border: 2px solid; border-radius: 24px; color: var(--kt-ink); text-decoration: none;
        box-shadow: 0 6px 0 var(--kt-line); transition: transform .15s, box-shadow .15s;
    }
    .kt-kartu:nth-child(odd)  { transform: rotate(-.6deg); }
    .kt-kartu:nth-child(even) { transform: rotate(.6deg); }
    .kt-kartu:hover { transform: translateY(-5px) rotate(0deg); box-shadow: 0 11px 0 var(--kt-line); color: var(--kt-ink); }

    .kt-kartu-bunga { position: absolute; top: -16px; right: -10px; width: 52px; height: 52px; transform: rotate(14deg); }
    .kt-kode {
        align-self: flex-start; margin-bottom: 10px; padding: 3px 12px; border-radius: 999px;
        background: rgba(255, 255, 255, .75); font-size: .72rem; font-weight: 700; letter-spacing: .6px;
    }
    .kt-kartu h3 {
        margin: 0 40px 8px 0; font-family: 'Fraunces', serif; font-weight: 600;
        font-size: 1.35rem; line-height: 1.2; letter-spacing: -.3px;
    }
    .kt-ket { margin: 0 0 16px; font-size: .92rem; line-height: 1.5; opacity: .88; flex: 1; }
    .kt-bawah { display: flex; align-items: center; justify-content: space-between; }
    .kt-jumlah {
        padding: 5px 14px; border-radius: 999px; background: var(--kt-deep); color: #fff;
        font-size: .8rem; font-weight: 700; box-shadow: 0 3px 0 #b84f65; text-decoration: none;
    }
    a.kt-jumlah:hover { color: #fff; filter: brightness(1.05); }
    .kt-panah { font-weight: 700; font-size: 1.2rem; transition: transform .15s; }
    .kt-kartu:hover .kt-panah { transform: translateX(5px); }

    .kt-info { text-align: center; margin: 0 0 24px; font-size: .95rem; opacity: .85; }

    .kt-kosong {
        text-align: center; padding: 40px 20px; background: rgba(255, 250, 251, .85);
        border: 2px dashed var(--kt-rose); border-radius: 22px;
    }
    .kt-kosong strong { display: block; margin-bottom: 6px; font-size: 1.1rem; }

    .kt-kartu:focus-visible, a.kt-jumlah:focus-visible { outline: 3px solid var(--kt-ink); outline-offset: 3px; }
    @media (max-width: 520px) { .kt-judul h2 { font-size: 1.9rem; } .kt-judul svg { width: 40px; height: 40px; } }
    @media (prefers-reduced-motion: reduce) {
        .kt-kelopak, .kt-judul svg { animation: none; }
        .kt-hujan { display: none; }
        .kt-kartu, .kt-kartu:nth-child(odd), .kt-kartu:nth-child(even) { transform: none; transition: none; }
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
html[data-bs-theme="dark"] .kt-wrap { --kt-card: #26151e; --kt-rose: #8f4560; --kt-deep: #f08fab; --kt-ink: #ffe3ec; --kt-line: #46283a; }
html[data-bs-theme="dark"] .kt-sub span { background: rgba(38, 21, 30, .85); }
html[data-bs-theme="dark"] .kt-kode { background: rgba(0, 0, 0, .28); }
html[data-bs-theme="dark"] .kt-jumlah { color: #3a1523; box-shadow: 0 3px 0 #b9627f; }
html[data-bs-theme="dark"] a.kt-jumlah:hover { color: #3a1523; }
html[data-bs-theme="dark"] .kt-cari button { color: #3a1523; box-shadow: 0 4px 0 #b9627f; }
html[data-bs-theme="dark"] .kt-kosong { background: rgba(38, 21, 30, .9); }
html[data-bs-theme="dark"] .kt-kartu:nth-child(4n+1) { background: #3a1f2b !important; border-color: #8f4560 !important; }
html[data-bs-theme="dark"] .kt-kartu:nth-child(4n+2) { background: #3a2a22 !important; border-color: #a8714f !important; }
html[data-bs-theme="dark"] .kt-kartu:nth-child(4n+3) { background: #2f2540 !important; border-color: #6b57a0 !important; }
html[data-bs-theme="dark"] .kt-kartu:nth-child(4n+4) { background: #3a1f33 !important; border-color: #94467a !important; }
</style>

<!-- Definisi gambar bunga sakura & kelopak (dipakai berulang) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <radialGradient id="kt-gradien" cx="50%" cy="85%" r="90%">
            <stop offset="0%" stop-color="#f4a7b9"/>
            <stop offset="100%" stop-color="#ffe3ea"/>
        </radialGradient>

        <g id="kt-kelopak-satu">
            <path d="M0 0 C-12 -8 -13 -26 -4 -31 L0 -27 L4 -31 C13 -26 12 -8 0 0 Z"
                  fill="url(#kt-gradien)" stroke="#f4a7b9" stroke-width="1"/>
        </g>

        <g id="kt-bunga">
            <use href="#kt-kelopak-satu" transform="rotate(0)"/>
            <use href="#kt-kelopak-satu" transform="rotate(72)"/>
            <use href="#kt-kelopak-satu" transform="rotate(144)"/>
            <use href="#kt-kelopak-satu" transform="rotate(216)"/>
            <use href="#kt-kelopak-satu" transform="rotate(288)"/>
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
<div class="kt-hujan" aria-hidden="true">
    <?php foreach ($kelopak as $k) { ?>
        <svg class="kt-kelopak" viewBox="-14 -32 28 34"
             style="left:<?= $k[0] ?>%;width:<?= $k[1] ?>px;height:<?= $k[1] ?>px;animation-delay:<?= $k[2] ?>s;animation-duration:<?= $k[3] ?>s;">
            <use href="#kt-kelopak-satu"/>
        </svg>
    <?php } ?>
</div>

<div class="kt-wrap">
    <div class="kt-judul">
        <svg viewBox="-34 -34 68 68" aria-hidden="true"><use href="#kt-bunga"/></svg>
        <h2>Cari Buku</h2>
        <svg viewBox="-34 -34 68 68" aria-hidden="true"><use href="#kt-bunga"/></svg>
    </div>
    <p class="kt-sub"><span>Ketik sebagian atau seluruh judul buku</span></p>

    <form action="<?= url('/siswa/cari') ?>" method="GET" class="kt-cari">
        <input type="text" name="q" value="<?= htmlspecialchars((string) $kata) ?>" placeholder="Cari judul buku..." autocomplete="off" aria-label="Cari judul buku">
        <button type="submit">Cari</button>
    </form>

    <p class="kt-info">
        <?php if ($kata === '') { ?>
            Menampilkan semua buku (<?= count($hasil) ?> judul)
        <?php } else { ?>
            <?= count($hasil) ?> buku ditemukan untuk &ldquo;<?= htmlspecialchars((string) $kata) ?>&rdquo;
        <?php } ?>
    </p>

    <?php if (count($hasil) === 0) { ?>
        <div class="kt-kosong">
            <strong>Buku tidak ditemukan</strong>
            Coba kata kunci lain, atau cari dengan sebagian judul saja.
        </div>
    <?php } else { ?>
        <?php $i = 0; ?>
        <div class="kt-grid">
            <?php foreach ($hasil as $b) {
                $w = $warna[$i % count($warna)];
                $i++;
            ?>
                <div class="kt-kartu" style="background:<?= $w[0] ?>;border-color:<?= $w[1] ?>;">
                    <svg class="kt-kartu-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#kt-bunga"/></svg>
                    <span class="kt-kode">TERBIT <?= htmlspecialchars((string) $b->tahun_terbit) ?></span>
                    <h3><?= htmlspecialchars((string) $b->judul_buku) ?></h3>
                    <p class="kt-ket">
                        Pengarang: <?= htmlspecialchars((string) $b->pengarang) ?><br>
                        Penerbit: <?= htmlspecialchars((string) $b->penerbit) ?>
                    </p>
                    <div class="kt-bawah">
                        <a class="kt-jumlah" href="<?= url('/siswa/peminjaman/create?buku=' . urlencode((string) $b->id_buku)) ?>">Pinjam buku</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php
// ===== Menu samping siswa: Kategori & Pengembalian Buku =====
$sbPath     = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$sbKategori = strpos($sbPath, '/siswa/kategori') === 0 || $sbPath === '/siswa/cari' || $sbPath === '/siswa/peminjaman/create';
$sbKembali  = $sbPath === '/siswa/peminjaman';
$sbDashboard = $sbPath === '/siswa/dashboard';
$sbUser     = null;
try { $sbUser = \App\Models\User::current(); } catch (\Throwable $e) { $sbUser = null; }
?>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;1,500&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    .sb, .sb *, .sb-buka { box-sizing: border-box; }
    .sb, .sb-buka, .sb-overlay {
        --pk-card: #fffafb; --pk-soft: #ffd6e0; --pk-rose: #f4a7b9;
        --pk-deep: #d9687f; --pk-ink: #6b2a3f; --pk-line: #f7c8d4; --sb-lebar: 270px;
    }

    .sb-buka {
        position: fixed; top: 1rem; left: 1rem; z-index: 1030;
        width: 46px; height: 46px; display: flex; align-items: center; justify-content: center;
        background: var(--pk-card); color: var(--pk-ink);
        border: 2px solid var(--pk-rose); border-radius: 14px; cursor: pointer;
        box-shadow: 0 3px 0 var(--pk-line); transition: transform .1s, box-shadow .1s, background .15s;
    }
    .sb-buka:hover { background: var(--pk-soft); transform: translateY(1px); box-shadow: 0 2px 0 var(--pk-line); }

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
    /* Tombol tema berbentuk bunga sakura (titik tengah hijau = database terhubung) */
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
    .sb-btn-pink { background: var(--pk-deep); border: 0; color: #fff; box-shadow: 0 4px 0 #b84f65; }

    /* ===== Mode gelap ===== */
    html[data-bs-theme="dark"] .sb,
    html[data-bs-theme="dark"] .sb-buka,
    html[data-bs-theme="dark"] .sb-overlay {
        --pk-card: #24141c; --pk-soft: #3b2230; --pk-rose: #8f4560;
        --pk-deep: #f08fab; --pk-ink: #ffe3ec; --pk-line: #46283a;
    }
    html[data-bs-theme="dark"] .sb.terbuka { box-shadow: 6px 0 24px rgba(0, 0, 0, .45); }
    html[data-bs-theme="dark"] .sb-overlay { background: rgba(0, 0, 0, .55); }
    html[data-bs-theme="dark"] .sb .flower { transform: rotate(36deg); }
    html[data-bs-theme="dark"] .sb .logo-toggle:hover .flower { transform: rotate(72deg) scale(1.1); }
    html[data-bs-theme="dark"] .sb .petal { fill: #5a2e41; stroke: #f08fab; }

    /* Isi halaman ikut bergeser saat menu dibuka (layar lebar) */
    body { transition: margin-left .25s ease; }
    @media (min-width: 900px) {
        body.sb-geser { margin-left: 270px; overflow-x: hidden; }
        .sb-overlay.terbuka { display: none; }
        .sb.terbuka { box-shadow: none; }
    }
    @media (max-width: 700px) { body { padding-top: 56px; } }
    @media (prefers-reduced-motion: reduce) { .sb, .sb.terbuka, body { transition: none; } }
</style>

<button type="button" id="sbBuka" class="sb-buka" aria-label="Buka menu" aria-controls="sbPanel" aria-expanded="false">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>
    </svg>
</button>

<div class="sb-overlay" id="sbOverlay"></div>

<aside class="sb" id="sbPanel" aria-label="Menu samping">
    <div class="sb-brand">
        <button id="sbTema" type="button" class="logo-toggle" aria-label="Ganti tema terang/gelap" title="Ganti tema terang/gelap">
            <svg width="38" height="38" viewBox="-20 -20 40 40" xmlns="http://www.w3.org/2000/svg" style="display:block" aria-hidden="true">
                <g class="flower">
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(0)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(72)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(144)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(216)"/>
                    <path class="petal" d="M0 0 C-8 -5.5 -9 -16 -3 -19 L0 -16 L3 -19 C9 -16 8 -5.5 0 0 Z" transform="rotate(288)"/>
                    <circle class="flower-pusat" r="4.6" fill="#28a745"/>
                </g>
            </svg>
        </button>
        <a href="<?= url('/') ?>">Peminjaman Buku</a>
        <button type="button" id="sbTutup" class="sb-tutup" aria-label="Tutup menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/>
            </svg>
        </button>
    </div>

    <nav aria-label="Menu utama">
        <ul class="sb-menu">
            <li>
                <a class="sb-link <?= $sbDashboard ? 'aktif' : '' ?>" href="<?= url('/siswa/dashboard') ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a class="sb-link <?= $sbKategori ? 'aktif' : '' ?>" href="<?= url('/siswa/kategori') ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2" fill="currentColor"/>
                    </svg>
                    Kategori
                </a>
            </li>
            <li>
                <a class="sb-link <?= $sbKembali ? 'aktif' : '' ?>" href="<?= url('/siswa/peminjaman') ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 14 4 9l5-5"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/>
                    </svg>
                    Pengembalian Buku
                </a>
            </li>
        </ul>
    </nav>

    <div class="sb-bawah">
        <form method="POST" action="<?= url('/logout') ?>">
            @csrf
            <button type="submit" class="sb-btn">Logout<?= $sbUser ? ' (' . htmlspecialchars((string) $sbUser->username) . ')' : '' ?></button>
        </form>
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

        // Bunga = tombol ganti tema; pilihan disimpan di browser
        var tema = document.getElementById('sbTema');
        if (tema) {
            tema.addEventListener('click', function () {
                var root = document.documentElement;
                var baru = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-bs-theme', baru);
                try { localStorage.setItem('sakuci-theme', baru); } catch (e) {}
            });
        }
    })();
</script>