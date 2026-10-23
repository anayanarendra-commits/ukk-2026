<?php

use App\Controllers\Core\AuthController;
use App\Controllers\Core\DashboardController;
use App\Controllers\Core\DatabaseController;
use App\Controllers\Core\DocsController;
use App\Controllers\Core\RoleController;
use App\Controllers\Core\UserController;
use App\Controllers\KategoriController;
use App\Controllers\DataBukuController;
use App\Controllers\PeminjamanController;
use App\Controllers\PengembalianController;
use App\Controllers\SiswaController;
use Sakuci\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/docs', [DocsController::class, 'index'])->name('docs');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt')->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat')->middleware('auth');

// Alamat lama: dialihkan ke riwayat peminjaman
Route::get('/pengembalian/riwayat', function () {
    return redirect()->route('peminjaman.riwayat');
})->name('pengembalian.riwayat')->middleware('auth');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::get('/database/export', [DatabaseController::class, 'export'])->name('admin.database.export');

    //Kategori routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/edit/{id_kategori}', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::delete('/kategori/{id_kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::put('/kategori/{id_kategori}', [KategoriController::class, 'update'])->name('kategori.update');

    //DataBuku routes
    Route::get('/data_buku', [DataBukuController::class, 'index'])->name('data_buku.index');
    Route::get('/data_buku/create', [DataBukuController::class, 'create'])->name('data_buku.create');
    Route::post('/data_buku/store', [DataBukuController::class, 'store'])->name('data_buku.store');
    Route::get('/data_buku/edit/{id_buku}', [DataBukuController::class, 'edit'])->name('data_buku.edit');
    Route::delete('/data_buku/{id_buku}', [DataBukuController::class, 'destroy'])->name('data_buku.destroy');
    Route::put('/data_buku/{id_buku}', [DataBukuController::class, 'update'])->name('data_buku.update');
    Route::get('/kategori/databuku', [DataBukuController::class, 'index'])->name('admin.kategori.databuku');

    //Peminjaman routes
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/edit/{id_peminjaman}', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::delete('/peminjaman/{id_peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::put('/peminjaman/{id_peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::put('/peminjaman/{id_peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::put('/peminjaman/{id_peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');

    //Pengembalian routes
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian/store', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('/pengembalian/edit/{id_buku}', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::delete('/pengembalian/{id_buku}', [PengembalianController::class, 'destroy'])->name('pengembalian.destroy');
    Route::put('/pengembalian/{id_buku}', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::get('/kategori/databuku/peminjaman/pengembalian', [PengembalianController::class, 'index'])->name('admin.kategori.databuku.peminjaman.pengembalian');
});

//Rute khusus siswa (role peminjam)
Route::group(['prefix' => 'siswa', 'middleware' => 'peminjam'], function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/cari', [SiswaController::class, 'cari'])->name('siswa.cari');

    Route::get('/kategori', [SiswaController::class, 'kategori'])->name('siswa.kategori');
    Route::get('/kategori/{id_kategori}', [SiswaController::class, 'bukuKategori'])->name('siswa.kategori.buku');

    Route::get('/peminjaman', [SiswaController::class, 'index'])->name('siswa.peminjaman.index');
    Route::get('/peminjaman/create', [SiswaController::class, 'create'])->name('siswa.peminjaman.create');
    Route::post('/peminjaman/store', [SiswaController::class, 'store'])->name('siswa.peminjaman.store');
    Route::post('/peminjaman/{id_peminjaman}/kembalikan', [SiswaController::class, 'kembalikan'])->name('siswa.peminjaman.kembalikan');
    Route::post('/peminjaman/{id_peminjaman}/bayar-denda', [SiswaController::class, 'bayarDenda'])->name('siswa.peminjaman.bayar');
});

/*
|--------------------------------------------------------------------------
| Route role dinamis
|--------------------------------------------------------------------------
| Blok di bawah ini dikelola otomatis oleh RoleController saat admin
| menambah, mengganti nama, atau menghapus role lewat /admin/roles.
| Jangan diedit manual -- perubahan bisa tertimpa.
*/
// @generated-roles:start

// @role:peminjam:start
Route::group(['prefix' => 'peminjam', 'middleware' => 'peminjam'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('peminjam.dashboard');
});
// @role:peminjam:end
// @generated-roles:end