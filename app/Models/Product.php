<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['kategori_id', 'kode_produk', 'nama_produk', 'harga', 'gambar', 'is_custom'];

    // Setiap produk adalah milik satu kategori (BelongsTo)
    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }
}