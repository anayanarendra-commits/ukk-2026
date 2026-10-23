<?php
// View: siswa/kategori_buku (buku di dalam satu kategori)
// Variabel dari controller: $kategori, $buku (array)
// Klik kartu buku -> langsung ke halaman pengajuan dengan buku itu sudah terpilih.

// Kelopak yang jatuh pelan-pelan: [posisi kiri %, ukuran px, delay detik, durasi detik]
$kelopak = [
    [6, 18, 0, 11], [18, 12, 3, 14], [31, 16, 6, 12], [47, 10, 1, 15],
    [60, 18, 4, 13], [73, 12, 8, 11], [86, 16, 2, 14], [94, 10, 7, 12],
];
?>
<script>
// Terapkan tema tersimpan lebih awal agar halaman tidak berkedip
(function () {
  try { document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('sakuci-theme') || 'light'); } catch (e) {}
})();
</script>
<style>
    .kb-wrap {
        --kb-bg: #fff0f3; --kb-card: #fffafb; --kb-soft: #ffd6e0; --kb-rose: #f4a7b9;
        --kb-deep: #d9687f; --kb-ink: #6b2a3f; --kb-line: #f7c8d4;
        position: relative; overflow: hidden; max-width: 860px; margin: 24px auto; padding: 28px 20px 36px;
        font-family: 'DM Sans', sans-serif; color: var(--kb-ink);
        background: linear-gradient(180deg, var(--kb-bg) 0%, #fff7f9 100%);
        border: 2px dashed var(--kb-rose); border-radius: 28px;
    }
    .kb-wrap * { box-sizing: border-box; }
    .kb-konten { position: relative; z-index: 2; }

    /* Kelopak jatuh */
    .kb-kelopak {
        position: absolute; top: -40px; z-index: 1; opacity: .75; pointer-events: none;
        animation: kb-jatuh linear infinite;
    }
    @keyframes kb-jatuh {
        0%   { transform: translateY(0) rotate(0deg) translateX(0); opacity: 0; }
        10%  { opacity: .8; }
        50%  { transform: translateY(260px) rotate(180deg) translateX(24px); }
        100% { transform: translateY(560px) rotate(360deg) translateX(-12px); opacity: 0; }
    }

    .kb-kembali {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; margin-bottom: 18px;
        border: 2px solid var(--kb-rose); border-radius: 999px; background: var(--kb-card);
        color: var(--kb-ink); font-weight: 700; font-size: .85rem; text-decoration: none;
        box-shadow: 0 3px 0 var(--kb-line); transition: transform .1s, box-shadow .1s, background .15s;
    }
    .kb-kembali:hover { background: var(--kb-soft); transform: translateY(1px); box-shadow: 0 2px 0 var(--kb-line); }

    /* Kartu judul kategori */
    .kb-hero {
        position: relative; display: flex; align-items: center; gap: 18px; padding: 22px 24px;
        background: var(--kb-card); border: 2px solid var(--kb-rose); border-radius: 22px;
        box-shadow: 0 5px 0 var(--kb-line); margin-bottom: 18px;
    }
    .kb-hero-bunga { flex: none; width: 76px; height: 76px; animation: kb-putar 28s linear infinite; }
    @keyframes kb-putar { to { transform: rotate(360deg); } }
    .kb-hero h2 {
        margin: 0 0 4px; font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.9rem;
        letter-spacing: -.5px; line-height: 1.1; color: var(--kb-ink);
    }
    .kb-hero p { margin: 0 0 10px; font-size: .95rem; opacity: .85; }
    .kb-lencana {
        display: inline-block; padding: 4px 14px; border-radius: 999px;
        background: var(--kb-soft); border: 2px solid var(--kb-rose); font-weight: 700; font-size: .8rem;
    }

    .kb-petunjuk { margin: 0 0 16px; text-align: center; font-size: .95rem; font-weight: 500; opacity: .85; }

    /* Daftar buku: tiap kartu adalah link ke halaman pengajuan */
    .kb-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
    .kb-buku {
        position: relative; display: flex; flex-direction: column; padding: 18px 18px 16px 18px;
        background: var(--kb-card); color: var(--kb-ink); text-decoration: none;
        border: 2px solid var(--kb-line); border-radius: 18px; box-shadow: 0 4px 0 var(--kb-line);
        transition: transform .15s, border-color .15s, box-shadow .15s;
    }
    .kb-buku:hover { color: var(--kb-ink); transform: translateY(-3px) rotate(-.4deg); border-color: var(--kb-rose); box-shadow: 0 7px 0 var(--kb-line); }
    .kb-buku-bunga { position: absolute; top: -12px; right: -8px; width: 34px; height: 34px; transform: rotate(18deg); }
    .kb-buku h3 {
        margin: 0 28px 6px 0; font-family: 'Fraunces', serif; font-weight: 600;
        font-size: 1.1rem; line-height: 1.25; color: var(--kb-ink);
    }
    .kb-meta { margin: 0; font-size: .85rem; line-height: 1.5; opacity: .85; }
    .kb-tahun {
        align-self: flex-start; margin-top: 10px; padding: 2px 10px; border-radius: 999px;
        background: var(--kb-soft); font-size: .75rem; font-weight: 700;
    }
    .kb-pilih {
        margin-top: auto; padding-top: 14px; display: flex; align-items: center; justify-content: space-between;
        font-size: .85rem; font-weight: 700; color: var(--kb-deep);
    }
    .kb-pilih span:last-child { transition: transform .15s; }
    .kb-buku:hover .kb-pilih span:last-child { transform: translateX(5px); }

    /* Kosong */
    .kb-kosong {
        text-align: center; padding: 34px 20px; background: var(--kb-card);
        border: 2px dashed var(--kb-rose); border-radius: 20px;
    }
    .kb-kosong svg { width: 64px; height: 64px; margin-bottom: 8px; opacity: .85; }
    .kb-kosong p { margin: 0; font-weight: 500; }

    .kb-kembali:focus-visible, .kb-buku:focus-visible { outline: 3px solid var(--kb-ink); outline-offset: 2px; }
    @media (max-width: 520px) {
        .kb-hero { flex-direction: column; text-align: center; }
        .kb-hero h2 { font-size: 1.6rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .kb-kelopak, .kb-hero-bunga { animation: none; }
        .kb-kelopak { display: none; }
        .kb-buku { transition: none; }
    }

    /* ===== Mode gelap ===== */
    html[data-bs-theme="dark"],
    html[data-bs-theme="dark"] body { background-color: #1b1016; }
    html[data-bs-theme="dark"] .kb-wrap {
        --kb-bg: #26151e; --kb-card: #2f1b26; --kb-soft: #46283a; --kb-rose: #8f4560;
        --kb-deep: #f08fab; --kb-ink: #ffe3ec; --kb-line: #46283a;
        background: linear-gradient(180deg, #26151e 0%, #1f1219 100%);
    }
</style>

<!-- Definisi gambar bunga sakura & kelopak (dipakai berulang) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <radialGradient id="kb-gradien" cx="50%" cy="85%" r="90%">
            <stop offset="0%" stop-color="#f4a7b9"/>
            <stop offset="100%" stop-color="#ffe3ea"/>
        </radialGradient>

        <g id="kb-kelopak-satu">
            <path d="M0 0 C-12 -8 -13 -26 -4 -31 L0 -27 L4 -31 C13 -26 12 -8 0 0 Z"
                  fill="url(#kb-gradien)" stroke="#f4a7b9" stroke-width="1"/>
        </g>

        <g id="kb-bunga">
            <use href="#kb-kelopak-satu" transform="rotate(0)"/>
            <use href="#kb-kelopak-satu" transform="rotate(72)"/>
            <use href="#kb-kelopak-satu" transform="rotate(144)"/>
            <use href="#kb-kelopak-satu" transform="rotate(216)"/>
            <use href="#kb-kelopak-satu" transform="rotate(288)"/>
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

<div class="kb-wrap">

    <!-- Kelopak jatuh (hiasan) -->
    <?php foreach ($kelopak as $k) { ?>
        <svg class="kb-kelopak" viewBox="-14 -32 28 34" aria-hidden="true"
             style="left:<?= $k[0] ?>%;width:<?= $k[1] ?>px;height:<?= $k[1] ?>px;animation-delay:<?= $k[2] ?>s;animation-duration:<?= $k[3] ?>s;">
            <use href="#kb-kelopak-satu"/>
        </svg>
    <?php } ?>

    <div class="kb-konten">
        <a class="kb-kembali" href="<?= url('/siswa/kategori') ?>">&larr; Semua kategori</a>

        <div class="kb-hero">
            <svg class="kb-hero-bunga" viewBox="-34 -34 68 68" aria-hidden="true">
                <use href="#kb-bunga"/>
            </svg>
            <div>
                <h2><?= htmlspecialchars((string) $kategori->nama_kategori) ?></h2>
                <p><?= htmlspecialchars((string) $kategori->keterangan) ?></p>
                <span class="kb-lencana"><?= count($buku) ?> buku</span>
            </div>
        </div>

        <?php if (count($buku) === 0) { ?>
            <div class="kb-kosong">
                <svg viewBox="-34 -34 68 68" aria-hidden="true"><use href="#kb-bunga"/></svg>
                <p>Belum ada buku di kategori ini.</p>
            </div>
        <?php } else { ?>
            <p class="kb-petunjuk">Klik buku yang ingin kamu pinjam.</p>

            <div class="kb-grid">
                <?php foreach ($buku as $b) {
                    $pengarang = trim((string) ($b->pengarang ?? ''));
                    $penerbit  = trim((string) ($b->penerbit ?? ''));
                    $tahun     = trim((string) ($b->tahun_terbit ?? ''));
                ?>
                    <a class="kb-buku" href="<?= url('/siswa/peminjaman/create?buku=' . (int) $b->id_buku) ?>">
                        <svg class="kb-buku-bunga" viewBox="-34 -34 68 68" aria-hidden="true"><use href="#kb-bunga"/></svg>
                        <h3><?= htmlspecialchars((string) $b->judul_buku) ?></h3>
                        <?php if ($pengarang !== '') { ?>
                            <p class="kb-meta">Oleh <?= htmlspecialchars($pengarang) ?></p>
                        <?php } ?>
                        <?php if ($penerbit !== '') { ?>
                            <p class="kb-meta"><?= htmlspecialchars($penerbit) ?></p>
                        <?php } ?>
                        <?php if ($tahun !== '') { ?>
                            <span class="kb-tahun"><?= htmlspecialchars($tahun) ?></span>
                        <?php } ?>
                        <span class="kb-pilih"><span>Pinjam buku ini</span><span aria-hidden="true">&rarr;</span></span>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
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