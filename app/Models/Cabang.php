<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';
    protected $fillable = ['kode_cabang', 'nama_cabang', 'saldo_stand', 'tabungan_wajib'];

    // Satu cabang punya banyak stok bahan dan transaksi (One-to-Many)
    public function stokBahan()
    {
        return $this->hasMany(StokBahan::class, 'cabang_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'cabang_id');
    }
    
    public function karyawan()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }
}