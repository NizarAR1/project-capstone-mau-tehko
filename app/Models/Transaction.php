<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['cabang_id', 'user_id', 'nama_pelanggan', 'total_tagihan', 'uang_diterima', 'status'];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Satu transaksi punya banyak rincian pesanan
    public function rincian()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }
}