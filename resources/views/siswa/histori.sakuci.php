<?php
// View: siswa/histori -> halaman "Histori Pengembalian" siswa (tema sakura)
// Isi: semua buku yang sudah dikembalikan siswa yang login.
// Variabel: $histori (array), $denda, $buku, $kata

$tarifDenda = \App\Controllers\SiswaController::DENDA_PER_HARI;

// Format tanggal d-m-Y, kosong jadi "-"
$fmtTgl = function ($v) {
    $v = substr((string) $v, 0, 10);
    return $v !== '' ? date('d-m-Y', strtotime($v)) : '-';
};
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

.sk-wrap { position: relative; z-index: 1; max-width: 980px; margin: 0 auto; padding: 40px 20px 80px; }

.sk-title { font-size: 2.1rem; font-weight: 700; margin: 0 0 6px; }
.sk-title::before { content: "✿"; color: var(--petal-deep); margin-right: 10px; font-weight: 400; }
.sk-sub { margin: 0 0 20px; color: var(--muted); }

.sk-filter {
  background: #fff; border: 1.5px solid var(--line); border-radius: 22px 22px 22px 6px;
  padding: 18px 20px; margin-bottom: 20px;
  box-shadow: 0 10px 30px -18px rgba(217, 102, 138, .45);
}
.sk-filter-row { display: flex; flex-wrap: wrap; gap: 14px; align-items: flex-end; }
.sk-filter label { display: block; color: var(--muted); font-size: .85rem; font-weight: 500; margin-bottom: 4px; }
.sk-filter input {
  font-family: inherit; color: var(--plum); background: #fffafb;
  border: 1.5px solid var(--line); border-radius: 12px; padding: 8px 10px; outline: none;
  transition: border-color .15s, box-shadow .15s;
}
.sk-filter input:focus { border-color: var(--petal-deep); box-shadow: 0 0 0 4px rgba(242, 163, 184, .28); }

.sk-btn {
  display: inline-block; font-family: inherit; font-weight: 700; font-size: 1rem; color: #fff;
  background: linear-gradient(135deg, #f08fab, var(--rose)); border: 0; border-radius: 999px;
  padding: 9px 22px; cursor: pointer; text-decoration: none;
  box-shadow: 0 6px 14px -6px rgba(217, 102, 138, .7);
  transition: transform .12s, box-shadow .12s;
}
.sk-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px -8px rgba(217, 102, 138, .8); }
.sk-btn:focus-visible, .sk-link:focus-visible { outline: 3px solid var(--petal-deep); outline-offset: 2px; }

.sk-link { color: var(--rose); font-weight: 500; text-decoration: none; }
.sk-link:hover { text-decoration: underline; text-underline-offset: 3px; }
.sk-reset { margin-left: 10px; }

.sk-tablebox {
  background: #fff; border: 1.5px solid var(--line); border-radius: 22px; overflow-x: auto;
  box-shadow: 0 14px 34px -22px rgba(217, 102, 138, .55);
}
.sk-table { width: 100%; border-collapse: collapse; min-width: 720px; }
.sk-table th { background: var(--petal); font-weight: 700; text-align: left; padding: 14px; white-space: nowrap; }
.sk-table td { padding: 13px 14px; border-top: 1px solid var(--line); vertical-align: middle; }
.sk-table tbody tr:nth-child(even) td { background: #fffafb; }
.sk-table tbody tr:hover td { background: #fff0f4; }
.sk-empty { text-align: center; color: var(--muted); padding: 28px 14px !important; }

.sk-badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: .85rem; font-weight: 700; }
.sk-dikembalikan { background: #e6f5ea; color: #2f7a4a; }
.sk-terlambat    { background: #ffe0d6; color: #b4400f; }

.sk-ket { margin-top: 4px; font-size: .8rem; color: var(--muted); }
.sk-done { color: var(--muted); font-size: .9rem; }

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
  .sk-wrap { padding: 28px 12px 60px; }
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
html[data-bs-theme="dark"] .sk-filter,
html[data-bs-theme="dark"] .sk-tablebox { background: #26151e; box-shadow: none; }
html[data-bs-theme="dark"] .sk-filter input { background: #1b1016; }
html[data-bs-theme="dark"] .sk-table th { background: #3b2230; }
html[data-bs-theme="dark"] .sk-table tbody tr:nth-child(even) td { background: #2b1822; }
html[data-bs-theme="dark"] .sk-table tbody tr:hover td { background: #3b2230; }
html[data-bs-theme="dark"] .sk-btn { color: #3a1523; box-shadow: none; }
html[data-bs-theme="dark"] .sk-dikembalikan { background: rgba(74, 222, 128, .15); color: #86efac; }
html[data-bs-theme="dark"] .sk-terlambat    { background: rgba(251, 146, 60, .18); color: #fdba74; }
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
    <h2 class="sk-title">Histori Pengembalian</h2>
    <p class="sk-sub">Daftar semua buku yang sudah kamu kembalikan.</p>

    <!-- Cari judul -->
    <form method="GET" action="<?= url('/siswa/histori-pengembalian') ?>" class="sk-filter">
        <div class="sk-filter-row">
            <div>
                <label for="f-q">Judul buku</label>
                <input id="f-q" type="text" name="q" value="<?= htmlspecialchars($kata) ?>" placeholder="Cari judul...">
            </div>
            <div>
                <button type="submit" class="sk-btn">Cari</button>
                <a href="<?= url('/siswa/histori-pengembalian') ?>" class="sk-link sk-reset">Reset</a>
            </div>
        </div>
    </form>

    <div class="sk-tablebox">
    <table class="sk-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Dikembalikan</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($histori as $p) {
                $d = $denda[$p->id_peminjaman] ?? ['hari' => 0, 'total' => 0, 'lunas' => false];
                $judulBuku = isset($buku[$p->id_buku]) ? (string) $buku[$p->id_buku]->judul_buku : '-';
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($judulBuku) ?></td>
                    <td><?= $fmtTgl($p->tanggal_pinjam) ?></td>
                    <td><?= $fmtTgl($p->tanggal_wajib_kembali) ?></td>
                    <td><?= $fmtTgl($p->tanggal_dikembalikan) ?></td>
                    <td>
                        <?php if ($d['total'] > 0 && $d['lunas']) { ?>
                            <span class="sk-badge sk-dikembalikan">Lunas &middot; Rp <?= number_format($d['total'], 0, ',', '.') ?></span>
                            <div class="sk-ket">Terlambat <?= $d['hari'] ?> hari</div>
                        <?php } elseif ($d['total'] > 0) { ?>
                            <span class="sk-badge sk-terlambat">Belum lunas &middot; Rp <?= number_format($d['total'], 0, ',', '.') ?></span>
                            <div class="sk-ket">Terlambat <?= $d['hari'] ?> hari &middot; bayar di menu Pengembalian Buku</div>
                        <?php } else { ?>
                            <span class="sk-badge sk-dikembalikan">Tepat waktu</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            <?php if (count($histori) === 0) { ?>
                <tr>
                    <td colspan="6" class="sk-empty">Belum ada riwayat pengembalian.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
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
// ===== Menu samping siswa: Dashboard, Kategori, Pengembalian Buku, Histori Pengembalian =====
$sbPath      = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$sbKategori  = strpos($sbPath, '/siswa/kategori') === 0 || $sbPath === '/siswa/peminjaman/create';
$sbKembali   = $sbPath === '/siswa/peminjaman';
$sbDashboard = $sbPath === '/siswa/dashboard';
$sbHistori   = $sbPath === '/siswa/histori-pengembalian';
$sbUser      = null;
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
            <li>
                <a class="sb-link <?= $sbHistori ? 'aktif' : '' ?>" href="<?= url('/siswa/histori-pengembalian') ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l3 2"/>
                    </svg>
                    Histori Pengembalian
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