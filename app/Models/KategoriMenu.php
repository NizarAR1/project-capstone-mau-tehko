<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    protected $table = 'kategori_menu';
    protected $fillable = ['nama_kategori'];

    // Satu kategori punya banyak produk menu
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}