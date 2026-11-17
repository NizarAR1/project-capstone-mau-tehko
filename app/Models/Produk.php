<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Baris ini sangat penting agar Laravel tidak mencari tabel "produks"
    protected $table = 'produk';
    
    protected $fillable = ['kategori_id', 'kode_produk', 'nama_produk', 'harga', 'gambar', 'is_custom'];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }
}