<?php
// View: siswa/form -> konfirmasi pengajuan peminjaman (buku sudah dipilih dari kategori)
// Variabel: $buku, $kategori (boleh null), $tanggalPinjam, $tanggalKembali

$pesanError = function_exists('session') ? session('error') : null;
$tarifDenda = \App\Controllers\SiswaController::DENDA_PER_HARI;

$namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tgl = function ($ymd) use ($namaBulan) {
    $t = strtotime($ymd);
    return date('j', $t) . ' ' . $namaBulan[(int) date('n', $t)] . ' ' . date('Y', $t);
};

$kembaliKe = $kategori
    ? url('/siswa/kategori/' . $kategori->id_kategori)
    : url('/siswa/kategori');
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

<script>
// Terapkan tema tersimpan lebih awal agar halaman tidak berkedip
(function () {
  try { document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('sakuci-theme') || 'light'); } catch (e) {}
})();
</script>
<style>
:root {
  --bg: #fff4f6;
  --petal: #fbd0da;
  --petal-deep: #f2a3b8;
  --rose: #d9668a;
  --plum: #5b2a3e;
  --muted: #9a6b7b;
  --line: #f5d3dc;
}
* { box-sizing: border-box; }
html { background: var(--bg); }
body {
  margin: 0;
  font-family: "Zen Maru Gothic", "Segoe UI", system-ui, sans-serif;
  color: var(--plum);
  min-height: 100vh;
  background:
    radial-gradient(900px 400px at 100% 0%, #ffe3ea 0%, transparent 60%),
    radial-gradient(700px 400px at 0% 100%, #ffe9ef 0%, transparent 60%),
    var(--bg);
}

.sk-wrap { position: relative; z-index: 1; max-width: 620px; margin: 0 auto; padding: 40px 20px 80px; }

.sk-title { font-size: 2.1rem; font-weight: 700; margin: 0 0 6px; }
.sk-title::before { content: "✿"; color: var(--petal-deep); margin-right: 10px; font-weight: 400; }
.sk-sub { margin: 0 0 22px; color: var(--muted); }

.sk-flash {
  background: #fff; border: 1.5px solid #f7c9b8; border-left: 6px solid #c2410c;
  border-radius: 14px; padding: 12px 16px; margin-bottom: 16px;
}

.sk-card {
  background: #fff; border: 1.5px solid var(--line); border-radius: 22px 22px 22px 6px;
  padding: 22px; box-shadow: 0 14px 34px -22px rgba(217, 102, 138, .55);
}
.sk-buku { margin: 0 0 4px; font-size: 1.5rem; font-weight: 700; }
.sk-kat { margin: 0 0 18px; color: var(--muted); }

.sk-rincian { background: #fff4f6; border: 1.5px solid var(--line); border-radius: 14px; padding: 12px 16px; margin-bottom: 20px; }
.sk-rincian div { display: flex; justify-content: space-between; gap: 12px; padding: 5px 0; }
.sk-rincian span:first-child { color: var(--muted); }
.sk-rincian b { text-align: right; }
.sk-rincian .sk-tempo { border-top: 1px dashed var(--petal-deep); margin-top: 6px; padding-top: 10px; color: var(--rose); }
.sk-catatan { margin: 0 0 20px; font-size: .9rem; color: var(--muted); }

.sk-aksi { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; }
.sk-btn {
  display: inline-block; font-family: inherit; font-weight: 700; font-size: 1rem; color: #fff;
  background: linear-gradient(135deg, #f08fab, var(--rose)); border: 0; border-radius: 999px;
  padding: 10px 26px; cursor: pointer; text-decoration: none;
  box-shadow: 0 6px 14px -6px rgba(217, 102, 138, .7);
  transition: transform .12s, box-shadow .12s;
}
.sk-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px -8px rgba(217, 102, 138, .8); }
.sk-link { color: var(--rose); font-weight: 500; text-decoration: none; }
.sk-link:hover { text-decoration: underline; text-underline-offset: 3px; }
.sk-btn:focus-visible, .sk-link:focus-visible { outline: 3px solid var(--petal-deep); outline-offset: 2px; }

#sk-branch { position: fixed; top: 0; right: 0; width: min(380px, 55vw); pointer-events: none; z-index: 0; }
#sk-petals { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }
.sk-petal {
  position: absolute; top: -30px; border-radius: 80% 0 80% 0; opacity: .85;
  background: linear-gradient(135deg, #ffd9e2, #f4a6bb); animation: sk-jatuh linear infinite;
}
@keyframes sk-jatuh {
  0%   { transform: translate3d(0, -30px, 0) rotate(0deg); }
  50%  { transform: translate3d(60px, 55vh, 0) rotate(200deg); }
  100% { transform: translate3d(-30px, 112vh, 0) rotate(420deg); }
}
@media (prefers-reduced-motion: reduce) { #sk-petals { display: none; } }
@media (max-width: 720px) {
  .sk-wrap { padding: 28px 14px 60px; }
  .sk-title { font-size: 1.7rem; }
}

/* ===== Mode gelap ===== */
html[data-bs-theme="dark"] {
  --bg: #1b1016; --petal: #46283a; --petal-deep: #f08fab; --rose: #f08fab;
  --plum: #ffe3ec; --muted: #d9a3b6; --line: #4a2a3a;
}
html[data-bs-theme="dark"] body {
  background:
    radial-gradient(900px 400px at 100% 0%, rgba(240, 143, 171, .12) 0%, transparent 60%),
    radial-gradient(700px 400px at 0% 100%, rgba(167, 139, 250, .10) 0%, transparent 60%),
    #1b1016;
}
html[data-bs-theme="dark"] .sk-flash { background: #26151e; }
html[data-bs-theme="dark"] .sk-filter,
html[data-bs-theme="dark"] .sk-tablebox,
html[data-bs-theme="dark"] .sk-card { background: #26151e; box-shadow: none; }
html[data-bs-theme="dark"] .sk-filter input,
html[data-bs-theme="dark"] .sk-filter select { background: #1b1016; }
html[data-bs-theme="dark"] .sk-table th { background: #3b2230; }
html[data-bs-theme="dark"] .sk-table tbody tr:nth-child(even) td { background: #2b1822; }
html[data-bs-theme="dark"] .sk-table tbody tr:hover td { background: #3b2230; }
html[data-bs-theme="dark"] .sk-btn,
html[data-bs-theme="dark"] .sk-return { color: #3a1523; box-shadow: none; }
html[data-bs-theme="dark"] .sk-dipinjam     { background: rgba(240, 143, 171, .2);  color: #ffb3c8; }
html[data-bs-theme="dark"] .sk-dikembalikan { background: rgba(74, 222, 128, .15);  color: #86efac; }
html[data-bs-theme="dark"] .sk-terlambat    { background: rgba(251, 146, 60, .18);  color: #fdba74; }
html[data-bs-theme="dark"] .sk-diajukan     { background: rgba(250, 204, 21, .15);  color: #fde68a; }
html[data-bs-theme="dark"] .sk-denda { background: rgba(251, 146, 60, .15); color: #fdba74; border-color: rgba(251, 146, 60, .4); }
html[data-bs-theme="dark"] .sk-denda:hover { background: rgba(251, 146, 60, .25); }
html[data-bs-theme="dark"] .sk-dialog { background: #26151e; }
html[data-bs-theme="dark"] .sk-rincian { background: #1b1016; }
html[data-bs-theme="dark"] .sk-petal { opacity: .45; }
</style>

<!-- Ranting sakura -->
<svg id="sk-branch" viewBox="0 0 380 230" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <g id="bunga">
      <g fill="#fbc3d1" stroke="#f08fab" stroke-width="1">
        <ellipse cx="0" cy="-9" rx="6" ry="9"/>
        <ellipse cx="0" cy="-9" rx="6" ry="9" transform="rotate(72)"/>
        <ellipse cx="0" cy="-9" rx="6" ry="9" transform="rotate(144)"/>
        <ellipse cx="0" cy="-9" rx="6" ry="9" transform="rotate(216)"/>
        <ellipse cx="0" cy="-9" rx="6" ry="9" transform="rotate(288)"/>
      </g>
      <circle r="3" fill="#d9668a"/>
    </g>
  </defs>
  <path d="M390 18 C320 30 270 60 215 96 S110 160 70 215" fill="none" stroke="#7a4b4b" stroke-width="7" stroke-linecap="round"/>
  <path d="M268 62 C250 40 232 30 210 28" fill="none" stroke="#7a4b4b" stroke-width="4" stroke-linecap="round"/>
  <path d="M180 118 C196 140 200 160 196 182" fill="none" stroke="#7a4b4b" stroke-width="4" stroke-linecap="round"/>
  <use href="#bunga" transform="translate(330 28) scale(1.5)"/>
  <use href="#bunga" transform="translate(290 52) scale(1.2)"/>
  <use href="#bunga" transform="translate(205 28) scale(1.4) rotate(20)"/>
  <use href="#bunga" transform="translate(248 86) scale(1.7) rotate(-10)"/>
  <use href="#bunga" transform="translate(196 184) scale(1.3) rotate(35)"/>
  <use href="#bunga" transform="translate(160 128) scale(1.6)"/>
  <use href="#bunga" transform="translate(110 168) scale(1.2) rotate(15)"/>
  <use href="#bunga" transform="translate(76 208) scale(1.4)"/>
</svg>
<div id="sk-petals"></div>

<div class="sk-wrap">
    <h2 class="sk-title">Ajukan Peminjaman</h2>
    <p class="sk-sub">Cek rincian di bawah, lalu ajukan.</p>

    <?php if (!empty($pesanError) && is_string($pesanError)) { ?>
        <div class="sk-flash"><?= htmlspecialchars($pesanError) ?></div>
    <?php } ?>

    <div class="sk-card">
        <h3 class="sk-buku"><?= htmlspecialchars((string) $buku->judul_buku) ?></h3>
        <p class="sk-kat">
            <?= $kategori ? 'Kategori ' . htmlspecialchars((string) $kategori->nama_kategori) : '&nbsp;' ?>
        </p>

        <div class="sk-rincian">
            <div><span>Tanggal pinjam</span><b><?= $tgl($tanggalPinjam) ?></b></div>
            <div class="sk-tempo"><span>Harus kembali paling lambat</span><b><?= $tgl($tanggalKembali) ?></b></div>
        </div>

        <p class="sk-catatan">
            Lama pinjam 7 hari. Terlambat dikenai denda Rp <?= number_format($tarifDenda, 0, ',', '.') ?> per hari.
            Pengajuan akan diproses petugas.
        </p>

        <form method="POST" action="<?= url('/siswa/peminjaman/store') ?>" class="sk-aksi">
            @csrf
            <input type="hidden" name="id_buku" value="<?= (int) $buku->id_buku ?>">
            <button type="submit" class="sk-btn">Ajukan Peminjaman</button>
            <a href="<?= $kembaliKe ?>" class="sk-link">Batal</a>
        </form>
    </div>
</div>

<script>
// Kelopak sakura yang jatuh pelan
(function () {
  var wadah = document.getElementById('sk-petals');
  if (!wadah) return;
  for (var i = 0; i < 14; i++) {
    var k = document.createElement('span');
    var s = 0.7 + Math.random() * 0.9;
    k.className = 'sk-petal';
    k.style.left = (Math.random() * 100) + 'vw';
    k.style.width = k.style.height = (14 * s) + 'px';
    k.style.animationDuration = (9 + Math.random() * 9) + 's';
    k.style.animationDelay = (-Math.random() * 14) + 's';
    wadah.appendChild(k);
  }
})();
</script>

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