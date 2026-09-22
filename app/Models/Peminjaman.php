<?php

namespace App\Models;

use Sakuci\Database\Model;

class Peminjaman extends Model
{
    protected static ?string $table = 'peminjaman';

    protected string $primaryKey='id_kategori';
    protected array $fillable = ['nama_kategori','kode','keterangan'];
}

