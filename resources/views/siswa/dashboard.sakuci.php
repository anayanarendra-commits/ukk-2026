<?php
// View: siswa/dashboard (tema sakura, terang dan gelap)
// Variabel: $nama, $dipinjam, $menunggu, $terlambat, $totalDenda, $terdekat (array|null), $aktivitas (array)

$jam  = (int) date('G');
$sapa = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));

$namaHari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$tglHariIni = $namaHari[(int) date('w')] . ', ' . date('j') . ' ' . $namaBulan[(int) date('n')] . ' ' . date('Y');
$fmtTgl = function ($ymd) use ($namaBulan) {
    $t = strtotime($ymd);
    return date('j', $t) . ' ' . $namaBulan[(int) date('n', $t)] . ' ' . date('Y', $t);
};
$rp = function ($n) {
    return 'Rp ' . number_format((int) $n, 0, ',', '.');
};

// Kalimat di bawah sapaan menyesuaikan keadaan siswa
if ($terlambat > 0) {
    $pesan = 'Ada ' . $terlambat . ' buku yang lewat jatuh tempo. Kembalikan segera supaya dendanya tidak bertambah.';
} elseif ($dipinjam > 0) {
    $pesan = 'Kamu sedang meminjam ' . $dipinjam . ' buku. Selamat membaca!';
} elseif ($menunggu > 0) {
    $pesan = 'Pengajuanmu sedang menunggu persetujuan petugas.';
} else {
    $pesan = 'Rakmu masih kosong. Yuk cari bacaan baru yang seru.';
}

// Kelopak harian: 7 hari masa pinjam, terisi seiring hari berjalan
$kelopakTerisi = 0;
$labelSisa = '';
$kelasSisa = '';
if ($terdekat) {
    $sisa = (int) $terdekat['sisa'];
    $kelopakTerisi = max(0, min(7, 7 - $sisa));
    if ($sisa > 1) {
        $labelSisa = $sisa . ' hari lagi';
    } elseif ($sisa === 1) {
        $labelSisa = 'Besok jatuh tempo';
        $kelasSisa = 'segera';
    } elseif ($sisa === 0) {
        $labelSisa = 'Jatuh tempo hari ini';
        $kelasSisa = 'segera';
    } else {
        $labelSisa = 'Terlambat ' . abs($sisa) . ' hari';
        $kelasSisa = 'telat';
    }
}

$labelStatus = ['diajukan' => 'Menunggu', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan'];

// Kelopak yang jatuh: [posisi kiri %, ukuran px, delay detik, durasi detik]
$kelopakJatuh = [
    [5, 16, 0, 15], [15, 11, 4, 18], [27, 14, 8, 16], [38, 10, 2, 19],
    [49, 16, 6, 17], [60, 11, 10, 15], [70, 14, 1, 18], [80, 10, 5, 16],
    [89, 16, 9, 17], [96, 12, 3, 19],
];
$bentukKelopak = 'M0 0 C-12 -8 -13 -26 -4 -31 L0 -27 L4 -31 C13 -26 12 -8 0 0 Z';
?>
<style>
    html { background: #fff4f6; }
    body {
        margin: 0; min-height: 100vh;
        font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif;
        background:
            radial-gradient(900px 420px at 100% 0%, #ffe3ea 0%, transparent 60%),
            radial-gradient(700px 420px at 0% 100%, #ffe9ef 0%, transparent 60%),
            #fff4f6;
    }

    /* Warna bunga ditaruh di level halaman, karena gambar bunga ada di luar .ds-wrap.
       Murni CSS: berganti sendiri mengikuti mode terang/gelap, tanpa JavaScript. */
    :root { --ds-g1: #f4a7b9; --ds-g2: #ffe3ea; --ds-rose: #f4a7b9; --ds-deep: #d9668a; }
    html[data-bs-theme="dark"] { --ds-g1: #e58aa6; --ds-g2: #6b3350; --ds-rose: #f08fab; --ds-deep: #f08fab; }

    .ds-wrap {
        --ds-card: #fffafb; --ds-ink: #5b2a3e; --ds-muted: #9a6b7b; --ds-soft: #ffe3ea;
        --ds-rose: #f4a7b9; --ds-deep: #d9668a; --ds-line: #f5d3dc; --ds-glow: rgba(217, 102, 138, .35);
        --ds-on-deep: #fff; --ds-g1: #f4a7b9; --ds-g2: #ffe3ea;
        --ds-petal: 26px 8px 26px 8px; --ds-petal-sm: 12px 4px 12px 4px;
        position: relative; z-index: 1; max-width: 1040px; margin: 0 auto; padding: 44px 20px 80px;
        color: var(--ds-ink);
    }
    .ds-wrap * { box-sizing: border-box; }

    /* ---------- Hiasan ---------- */
    #ds-ranting { position: fixed; top: 0; right: 0; width: min(360px, 52vw); pointer-events: none; z-index: 0; }
    #ds-hujan { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }
    .ds-jatuh { position: absolute; top: -40px; opacity: 0; fill: var(--ds-rose, #f4a7b9); animation: ds-turun linear infinite; }
    @keyframes ds-turun {
        0%   { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
        8%   { opacity: .8; }
        50%  { transform: translateY(55vh) translateX(34px) rotate(200deg); }
        100% { transform: translateY(112vh) translateX(-18px) rotate(400deg); opacity: 0; }
    }

    /* ---------- Peringatan denda ---------- */
    .ds-awas {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px;
        padding: 12px 18px; border-radius: var(--ds-petal-sm); border: 2px solid #f5a98f;
        background: #ffe0d6; color: #8a3a1a; font-weight: 500;
    }
    .ds-awas a { margin-left: auto; color: inherit; font-weight: 700; text-underline-offset: 3px; }

    /* ---------- Sapaan ---------- */
    .ds-hero {
        position: relative; display: flex; align-items: center; justify-content: space-between; gap: 24px;
        padding: 34px 36px; margin-bottom: 26px; overflow: hidden;
        background: linear-gradient(135deg, var(--ds-card) 0%, var(--ds-soft) 100%);
        border: 2px solid var(--ds-rose); border-radius: 34px 10px 34px 10px;
        box-shadow: 0 8px 0 var(--ds-line), 0 24px 40px -28px var(--ds-glow);
    }
    .ds-sapa { margin: 0 0 4px; font-weight: 500; color: var(--ds-muted); }
    .ds-hero h1 {
        margin: 0 0 10px; font-family: 'Fraunces', Georgia, serif; font-weight: 600;
        font-size: clamp(2rem, 5vw, 3rem); line-height: 1.1; letter-spacing: -1px;
    }
    .ds-hero h1 em { font-style: italic; color: var(--ds-deep); }
    .ds-pesan { margin: 0 0 20px; max-width: 460px; line-height: 1.55; }
    .ds-tgl { display: inline-block; margin-bottom: 18px; padding: 4px 14px; border-radius: 999px; font-size: .82rem; font-weight: 700; background: var(--ds-card); border: 2px dashed var(--ds-rose); }
    .ds-aksi { display: flex; flex-wrap: wrap; gap: 12px; }
    .ds-btn {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; border-radius: 999px;
        font: inherit; font-weight: 700; text-decoration: none; cursor: pointer;
        background: var(--ds-deep); color: var(--ds-on-deep); border: 2px solid var(--ds-deep);
        box-shadow: 0 4px 0 rgba(0, 0, 0, .18); transition: transform .1s, box-shadow .1s, background .15s;
    }
    .ds-btn:hover { color: var(--ds-on-deep); transform: translateY(2px); box-shadow: 0 2px 0 rgba(0, 0, 0, .18); }
    .ds-btn.garis { background: transparent; color: var(--ds-ink); border-color: var(--ds-rose); box-shadow: none; }
    .ds-btn.garis:hover { color: var(--ds-ink); background: var(--ds-soft); transform: none; }
    .ds-btn:focus-visible, .ds-tile:focus-visible, .ds-awas a:focus-visible { outline: 3px solid var(--ds-ink); outline-offset: 3px; }

    .ds-bunga-besar { flex: none; width: clamp(130px, 22vw, 230px); aspect-ratio: 1; }
    .ds-bunga-besar svg { width: 100%; height: 100%; animation: ds-putar 40s linear infinite; filter: drop-shadow(0 12px 18px var(--ds-glow)); }
    @keyframes ds-putar { to { transform: rotate(360deg); } }

    /* ---------- Kartu statistik ---------- */
    .ds-stat { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 26px; }
    .ds-kartu {
        position: relative; padding: 22px 22px 20px; border: 2px solid var(--k-line); border-radius: var(--ds-petal);
        background: var(--k-bg); box-shadow: 0 6px 0 var(--ds-line);
        transition: transform .15s, box-shadow .15s;
    }
    .ds-kartu:hover { transform: translateY(-4px); box-shadow: 0 10px 0 var(--ds-line); }
    .ds-kartu .ikon { position: absolute; top: -14px; right: -8px; width: 54px; height: 54px; transform: rotate(14deg); }
    .ds-angka { margin: 6px 0 2px; font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: 2.6rem; line-height: 1; letter-spacing: -1px; color: var(--k-ink); }
    .ds-label { margin: 0; font-weight: 700; }
    .ds-ket { margin: 4px 0 0; font-size: .85rem; color: var(--ds-muted); }
    .k1 { --k-bg: #ffe3ea; --k-line: #f4a7b9; --k-ink: #b4426a; }
    .k2 { --k-bg: #fff1cf; --k-line: #f1d28b; --k-ink: #9a6a00; }
    .k3 { --k-bg: #ffe0d6; --k-line: #f5a98f; --k-ink: #b4400f; }
    .k4 { --k-bg: #f1e8ff; --k-line: #cdb6f0; --k-ink: #6a43b0; }

    /* ---------- Dua kolom: jatuh tempo & aktivitas ---------- */
    .ds-dua { display: grid; grid-template-columns: 1.1fr 1fr; gap: 22px; margin-bottom: 26px; }
    .ds-panel {
        padding: 24px; background: var(--ds-card); border: 2px solid var(--ds-line);
        border-radius: var(--ds-petal); box-shadow: 0 6px 0 var(--ds-line);
    }
    .ds-panel h2 { margin: 0 0 4px; font-family: 'Fraunces', Georgia, serif; font-weight: 600; font-size: 1.35rem; letter-spacing: -.3px; }
    .ds-panel .sub { margin: 0 0 18px; color: var(--ds-muted); font-size: .9rem; }

    .ds-tempo-judul { margin: 0 0 4px; font-family: 'Fraunces', Georgia, serif; font-size: 1.5rem; font-weight: 600; line-height: 1.2; }
    .ds-tempo-tgl { margin: 0 0 16px; color: var(--ds-muted); }
    .ds-chip { display: inline-block; padding: 4px 14px; border-radius: 999px; font-weight: 700; font-size: .85rem; background: var(--ds-soft); color: var(--ds-deep); border: 2px solid var(--ds-rose); }
    .ds-chip.segera { background: #fff1cf; color: #9a6a00; border-color: #f1d28b; }
    .ds-chip.telat  { background: #ffe0d6; color: #b4400f; border-color: #f5a98f; }

    .ds-hari { display: flex; gap: 8px; margin: 18px 0 8px; }
    .ds-hari svg { width: 100%; max-width: 44px; height: auto; flex: 1; }
    .ds-hari path { fill: var(--ds-soft); stroke: var(--ds-rose); stroke-width: 1.2; transition: fill .3s; }
    .ds-hari .on path { fill: var(--ds-deep); stroke: var(--ds-deep); }
    .ds-hari-ket { margin: 0; font-size: .82rem; color: var(--ds-muted); }

    .ds-kosong { text-align: center; padding: 18px 8px; color: var(--ds-muted); }
    .ds-kosong svg { width: 64px; height: 64px; margin-bottom: 6px; opacity: .85; }
    .ds-kosong p { margin: 0 0 14px; }

    .ds-daftar { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; }
    .ds-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 14px; border-radius: var(--ds-petal-sm); background: var(--ds-soft); }
    .ds-item b { display: block; font-weight: 700; line-height: 1.25; }
    .ds-item small { color: var(--ds-muted); }
    .ds-badge { flex: none; padding: 3px 12px; border-radius: 999px; font-size: .78rem; font-weight: 700; }
    .b-diajukan     { background: #fff1cf; color: #9a6a00; }
    .b-dipinjam     { background: #ffd0dc; color: #b4426a; }
    .b-dikembalikan { background: #dff3e5; color: #2f7a4a; }
    .b-telat        { background: #ffd2c2; color: #b4400f; }

    /* ---------- Pintasan ---------- */
    .ds-pintas { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
    .ds-pintas, .ds-aksi { position: relative; z-index: 2; }
    .ds-tile {
        cursor: pointer;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px;
        background: var(--ds-card); color: var(--ds-ink); text-decoration: none; font-weight: 700;
        border: 2px solid var(--ds-line); border-radius: var(--ds-petal-sm); box-shadow: 0 4px 0 var(--ds-line);
        transition: transform .15s, border-color .15s, box-shadow .15s;
    }
    .ds-tile:hover { color: var(--ds-ink); transform: translateY(-3px); border-color: var(--ds-rose); box-shadow: 0 7px 0 var(--ds-line); }
    .ds-tile span:last-child { color: var(--ds-deep); transition: transform .15s; }
    .ds-tile:hover span:last-child { transform: translateX(5px); }

    /* ---------- Angka "meletup" pelan (CSS saja) ---------- */
    .ds-angka .hitung { display: inline-block; animation: ds-pop .6s cubic-bezier(.2, 1.4, .4, 1) both; animation-delay: calc(var(--i, 0) * 80ms + 250ms); }
    @keyframes ds-pop { from { opacity: 0; transform: scale(.55); } to { opacity: 1; transform: none; } }

    /* ---------- Muncul pelan ---------- */
    .ds-muncul { opacity: 0; transform: translateY(14px); animation: ds-naik .55s ease forwards; animation-delay: calc(var(--i, 0) * 80ms); }
    @keyframes ds-naik { to { opacity: 1; transform: none; } }

    @media (max-width: 820px) {
        .ds-dua { grid-template-columns: 1fr; }
        .ds-hero { padding: 26px 22px; }
    }
    @media (max-width: 560px) {
        .ds-wrap { padding: 28px 14px 60px; }
        .ds-hero { flex-direction: column-reverse; text-align: center; align-items: center; }
        .ds-pesan { margin-inline: auto; }
        .ds-aksi { justify-content: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        #ds-hujan { display: none; }
        .ds-bunga-besar svg { animation: none; }
        .ds-muncul { animation: none; opacity: 1; transform: none; }
        .ds-angka .hitung { animation: none; }
        .ds-kartu, .ds-tile { transition: none; }
    }

    /* ---------- Mode gelap ---------- */
    html[data-bs-theme="dark"] { background: #1b0f15; }
    [data-bs-theme="dark"] .ds-wrap {
        --ds-card: #24141c; --ds-ink: #ffe3ec; --ds-muted: #c79aac; --ds-soft: #33202b;
        --ds-rose: #8f4560; --ds-deep: #f08fab; --ds-line: #46283a; --ds-glow: rgba(240, 143, 171, .22);
        --ds-on-deep: #3a1523; --ds-g1: #8f4560; --ds-g2: #3b2230;
    }
    [data-bs-theme="dark"] .ds-hero { background: linear-gradient(135deg, #2a1822 0%, #3a2030 100%); }
    [data-bs-theme="dark"] .ds-btn { box-shadow: 0 4px 0 #b9627f; }
    [data-bs-theme="dark"] .ds-btn:hover { box-shadow: 0 2px 0 #b9627f; }
    [data-bs-theme="dark"] .ds-btn.garis { box-shadow: none; color: #ffe3ec; }
    [data-bs-theme="dark"] .ds-awas { background: #3d2420; border-color: #b0654d; color: #ffb59a; }
    [data-bs-theme="dark"] .k1 { --k-bg: #3a1f2b; --k-line: #8f4560; --k-ink: #ffb3c8; }
    [data-bs-theme="dark"] .k2 { --k-bg: #3a3220; --k-line: #a68a3a; --k-ink: #ffd98a; }
    [data-bs-theme="dark"] .k3 { --k-bg: #3d2420; --k-line: #b0654d; --k-ink: #ffb59a; }
    [data-bs-theme="dark"] .k4 { --k-bg: #2f2540; --k-line: #6b57a0; --k-ink: #cdbbff; }
    [data-bs-theme="dark"] .ds-chip.segera { background: rgba(255, 200, 80, .16); color: #ffd98a; border-color: #a68a3a; }
    [data-bs-theme="dark"] .ds-chip.telat  { background: rgba(255, 140, 100, .18); color: #ffb59a; border-color: #b0654d; }
    [data-bs-theme="dark"] .b-diajukan     { background: rgba(255, 200, 80, .16); color: #ffd98a; }
    [data-bs-theme="dark"] .b-dipinjam     { background: rgba(240, 143, 171, .2); color: #ffb3c8; }
    [data-bs-theme="dark"] .b-dikembalikan { background: rgba(80, 200, 120, .16); color: #8fe0aa; }
    [data-bs-theme="dark"] .b-telat        { background: rgba(255, 140, 100, .18); color: #ffb59a; }
    [data-bs-theme="dark"] #ds-ranting { opacity: .75; }
    [data-bs-theme="dark"] .ds-jatuh { opacity: .5; }
</style>

<!-- Gambar bunga & kelopak yang dipakai berulang -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <radialGradient id="ds-grad" cx="50%" cy="85%" r="90%">
            <stop offset="0%" style="stop-color: var(--ds-g1, #f4a7b9)"/>
            <stop offset="100%" style="stop-color: var(--ds-g2, #ffe3ea)"/>
        </radialGradient>
        <g id="ds-kelopak">
            <path d="<?= $bentukKelopak ?>" fill="url(#ds-grad)" style="stroke: var(--ds-rose, #f4a7b9)" stroke-width="1"/>
        </g>
        <g id="ds-bunga">
            <use href="#ds-kelopak" transform="rotate(0)"/>
            <use href="#ds-kelopak" transform="rotate(72)"/>
            <use href="#ds-kelopak" transform="rotate(144)"/>
            <use href="#ds-kelopak" transform="rotate(216)"/>
            <use href="#ds-kelopak" transform="rotate(288)"/>
            <circle r="3.4" style="fill: var(--ds-deep, #d9668a)"/>
            <g style="stroke: var(--ds-deep, #d9668a)" stroke-width="1" stroke-linecap="round">
                <line x1="0" y1="0" x2="0" y2="-9"/><line x1="0" y1="0" x2="8.5" y2="-2.8"/>
                <line x1="0" y1="0" x2="5.3" y2="7.3"/><line x1="0" y1="0" x2="-5.3" y2="7.3"/>
                <line x1="0" y1="0" x2="-8.5" y2="-2.8"/>
            </g>
            <g style="fill: var(--ds-deep, #d9668a)">
                <circle cx="0" cy="-9" r="1.2"/><circle cx="8.5" cy="-2.8" r="1.2"/>
                <circle cx="5.3" cy="7.3" r="1.2"/><circle cx="-5.3" cy="7.3" r="1.2"/>
                <circle cx="-8.5" cy="-2.8" r="1.2"/>
            </g>
        </g>
    </defs>
</svg>

<!-- Ranting sakura -->
<svg id="ds-ranting" viewBox="0 0 380 230" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M390 18 C320 30 270 60 215 96 S110 160 70 215" fill="none" stroke="#7a4b4b" stroke-width="7" stroke-linecap="round"/>
    <path d="M268 62 C250 40 232 30 210 28" fill="none" stroke="#7a4b4b" stroke-width="4" stroke-linecap="round"/>
    <path d="M180 118 C196 140 200 160 196 182" fill="none" stroke="#7a4b4b" stroke-width="4" stroke-linecap="round"/>
    <use href="#ds-bunga" transform="translate(330 28) scale(.9)"/>
    <use href="#ds-bunga" transform="translate(290 52) scale(.7)"/>
    <use href="#ds-bunga" transform="translate(205 28) scale(.8) rotate(20)"/>
    <use href="#ds-bunga" transform="translate(248 86) scale(1) rotate(-10)"/>
    <use href="#ds-bunga" transform="translate(196 184) scale(.75) rotate(35)"/>
    <use href="#ds-bunga" transform="translate(160 128) scale(.95)"/>
    <use href="#ds-bunga" transform="translate(110 168) scale(.7) rotate(15)"/>
    <use href="#ds-bunga" transform="translate(76 208) scale(.8)"/>
</svg>

<!-- Kelopak jatuh -->
<div id="ds-hujan" aria-hidden="true">
    <?php foreach ($kelopakJatuh as $k) { ?>
        <svg class="ds-jatuh" viewBox="-14 -32 28 34"
             style="left:<?= $k[0] ?>%;width:<?= $k[1] ?>px;height:<?= $k[1] ?>px;animation-delay:<?= $k[2] ?>s;animation-duration:<?= $k[3] ?>s;">
            <path d="<?= $bentukKelopak ?>"/>
        </svg>
    <?php } ?>
</div>

<div class="ds-wrap">

    <?php if ($totalDenda > 0) { ?>
        <div class="ds-awas ds-muncul" style="--i:0" role="status">
            <span>Kamu punya denda <b><?= $rp($totalDenda) ?></b> yang belum dibayar.</span>
            <a href="<?= url('/siswa/peminjaman') ?>">Lihat di Pengembalian &rarr;</a>
        </div>
    <?php } ?>

    <!-- Sapaan -->
    <section class="ds-hero ds-muncul" style="--i:1">
        <div>
            <span class="ds-tgl"><?= $tglHariIni ?></span>
            <p class="ds-sapa"><?= $sapa ?>,</p>
            <h1>Halo, <em><?= htmlspecialchars((string) $nama) ?></em></h1>
            <p class="ds-pesan"><?= htmlspecialchars($pesan) ?></p>
            <div class="ds-aksi">
                <a class="ds-btn" href="<?= url('/siswa/kategori') ?>">Jelajahi kategori</a>
                <a class="ds-btn garis" href="<?= url('/siswa/peminjaman') ?>">Kembalikan buku</a>
            </div>
        </div>
        <div class="ds-bunga-besar" aria-hidden="true">
            <svg viewBox="-34 -34 68 68"><use href="#ds-bunga"/></svg>
        </div>
    </section>

    <!-- Ringkasan -->
    <section class="ds-stat" aria-label="Ringkasan peminjaman">
        <div class="ds-kartu k1 ds-muncul" style="--i:2">
            <svg class="ikon" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
            <p class="ds-angka"><span class="hitung" data-nilai="<?= (int) $dipinjam ?>"><?= (int) $dipinjam ?></span></p>
            <p class="ds-label">Sedang dipinjam</p>
            <p class="ds-ket">Buku yang kamu bawa sekarang</p>
        </div>
        <div class="ds-kartu k2 ds-muncul" style="--i:3">
            <svg class="ikon" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
            <p class="ds-angka"><span class="hitung" data-nilai="<?= (int) $menunggu ?>"><?= (int) $menunggu ?></span></p>
            <p class="ds-label">Menunggu persetujuan</p>
            <p class="ds-ket">Pengajuan yang belum di-ACC</p>
        </div>
        <div class="ds-kartu k3 ds-muncul" style="--i:4">
            <svg class="ikon" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
            <p class="ds-angka"><span class="hitung" data-nilai="<?= (int) $terlambat ?>"><?= (int) $terlambat ?></span></p>
            <p class="ds-label">Terlambat</p>
            <p class="ds-ket">Lewat dari batas 7 hari</p>
        </div>
        <div class="ds-kartu k4 ds-muncul" style="--i:5">
            <svg class="ikon" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
            <p class="ds-angka"><span class="hitung" data-rp="1" data-nilai="<?= (int) $totalDenda ?>"><?= $rp($totalDenda) ?></span></p>
            <p class="ds-label">Denda belum dibayar</p>
            <p class="ds-ket">Rp 1.000 per hari terlambat</p>
        </div>
    </section>

    <!-- Jatuh tempo & aktivitas -->
    <section class="ds-dua">
        <div class="ds-panel ds-muncul" style="--i:6">
            <h2>Jatuh tempo terdekat</h2>
            <p class="sub">Tiap kelopak adalah satu hari dari masa pinjam 7 hari.</p>

            <?php if ($terdekat) { ?>
                <p class="ds-tempo-judul"><?= htmlspecialchars((string) $terdekat['judul']) ?></p>
                <p class="ds-tempo-tgl">Kembali paling lambat <?= $fmtTgl($terdekat['wajib']) ?></p>
                <span class="ds-chip <?= $kelasSisa ?>"><?= $labelSisa ?></span>

                <div class="ds-hari" role="img" aria-label="<?= $kelopakTerisi ?> dari 7 hari masa pinjam sudah berjalan">
                    <?php for ($i = 1; $i <= 7; $i++) { ?>
                        <svg viewBox="-14 -32 28 34" class="<?= $i <= $kelopakTerisi ? 'on' : '' ?>"><path d="<?= $bentukKelopak ?>"/></svg>
                    <?php } ?>
                </div>
                <p class="ds-hari-ket"><?= $kelopakTerisi ?> dari 7 hari sudah berjalan</p>
            <?php } else { ?>
                <div class="ds-kosong">
                    <svg viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
                    <p>Belum ada buku yang sedang dipinjam.</p>
                    <a class="ds-btn" href="<?= url('/siswa/kategori') ?>">Pilih buku</a>
                </div>
            <?php } ?>
        </div>

        <div class="ds-panel ds-muncul" style="--i:7">
            <h2>Aktivitas terbaru</h2>
            <p class="sub">Lima peminjaman terakhirmu.</p>

            <?php if (count($aktivitas) > 0) { ?>
                <ul class="ds-daftar">
                    <?php foreach ($aktivitas as $a) {
                        $kelasBadge = $a['telat'] ? 'b-telat' : 'b-' . preg_replace('/[^a-z]/', '', $a['status']);
                        $teksBadge  = $a['telat'] ? 'Terlambat' : ($labelStatus[$a['status']] ?? $a['status']);
                    ?>
                        <li class="ds-item">
                            <div>
                                <b><?= htmlspecialchars((string) $a['judul']) ?></b>
                                <small><?= $a['tanggal'] !== '' ? $fmtTgl($a['tanggal']) : '-' ?></small>
                            </div>
                            <span class="ds-badge <?= $kelasBadge ?>"><?= htmlspecialchars((string) $teksBadge) ?></span>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <div class="ds-kosong">
                    <svg viewBox="-34 -34 68 68" aria-hidden="true"><use href="#ds-bunga"/></svg>
                    <p>Belum ada aktivitas. Pinjam buku pertamamu!</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Pintasan -->
    <section class="ds-pintas ds-muncul" style="--i:8" aria-label="Pintasan">
        <a class="ds-tile" href="<?= url('/siswa/kategori') ?>"><span>Pilih buku dari kategori</span><span aria-hidden="true">&rarr;</span></a>
        <a class="ds-tile" href="<?= url('/siswa/peminjaman') ?>"><span>Kembalikan buku &amp; bayar denda</span><span aria-hidden="true">&rarr;</span></a>
    </section>
</div>

<?php
// ===== Menu samping siswa: Kategori & Pengembalian Buku =====
$sbPath     = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$sbKategori = strpos($sbPath, '/siswa/kategori') === 0 || $sbPath === '/siswa/peminjaman/create';
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