<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    // Memastikan nama tabel benar (yang sudah Anda kerjakan sebelumnya)
    protected $table = 'transaksi_detail';

    // TAMBAHKAN FUNGSI INI:
    // Memberi tahu Laravel bahwa setiap 1 baris detail ini adalah MILIK 1 transaksi induk
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
    
    // (Opsional tapi penting ke depannya) Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}