<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Cabang
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cabang')->unique();
            $table->string('nama_cabang');
            $table->integer('saldo_stand')->default(0);
            $table->integer('tabungan_wajib')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Kategori Menu
        Schema::create('kategori_menu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // 3. Update Tabel Users (Bawaan Laravel)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->constrained('cabang');
            $table->enum('role', ['kasir', 'leader'])->default('kasir');
            $table->integer('total_tabungan')->default(0);
        });

        // 4. Tabel Produk (Menu)
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_menu');
            $table->string('kode_produk')->unique(); 
            $table->string('nama_produk');
            $table->integer('harga');
            $table->string('gambar')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->timestamps();
        });

        // 5. Tabel Stok Bahan Baku
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->string('kode_bahan'); 
            $table->string('nama_bahan');
            $table->integer('jumlah_stok')->default(0);
            $table->string('satuan'); 
            $table->timestamps();
        });

        // 6. Tabel Transaksi Induk
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->foreignId('user_id')->constrained('users'); 
            $table->string('nama_pelanggan')->nullable(); 
            $table->integer('total_tagihan');
            $table->integer('uang_diterima')->nullable();
            $table->enum('status', ['antrean', 'selesai'])->default('selesai');
            $table->timestamps();
        });

        // 7. Tabel Transaksi Detail (Pesanan)
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->nullable()->constrained('produk'); 
            $table->string('nama_custom')->nullable();
            $table->integer('harga_satuan');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Penghapusan harus dilakukan dari bawah ke atas agar tidak kena error relasi
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('stok_bahan');
        Schema::dropIfExists('produk');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn(['cabang_id', 'role', 'total_tabungan']);
        });
        Schema::dropIfExists('kategori_menu');
        Schema::dropIfExists('cabang');
    }
};