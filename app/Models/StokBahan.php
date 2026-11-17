<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    protected $table = 'stok_bahan';
    protected $fillable = ['cabang_id', 'kode_bahan', 'nama_bahan', 'jumlah_stok', 'satuan'];

    // Stok ini milik cabang tertentu
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}