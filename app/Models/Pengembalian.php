<?php

namespace App\Models;

use Sakuci\Database\Model;

class Pengembalian extends Model
{
    protected static ?string $table = 'pengembalian';

    protected string $primaryKey = 'id_pengembalian';
    protected array $fillable = ['id_peminjaman', 'tanggal_dikembalikan', 'kondisi_buku', 'denda', 'catatan_admin'];
}