<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    
    // Daftar kolom yang boleh diisi
    protected $fillable = ['kategori_id', 'nama_menu', 'harga'];

    // Relasi: Setiap Menu milik satu Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}