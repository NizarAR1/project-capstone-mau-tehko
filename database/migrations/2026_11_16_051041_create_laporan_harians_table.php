<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harians', function (Blueprint $table) {
            $table->id();
            $table->integer('cabang_id'); // ID Cabang (Misal: 1 untuk Kanal)
            $table->date('tanggal'); // Tanggal laporan ini dibuat
            $table->integer('uang_laci')->default(0); // Input fisik dari kasir
            $table->integer('uang_qris')->default(0); // Input fisik dari kasir
            $table->integer('total_penjualan')->default(0); // Omzet total hari itu
            $table->integer('total_pengeluaran')->default(0); // Total dari semua pengeluaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harians');
    }
};