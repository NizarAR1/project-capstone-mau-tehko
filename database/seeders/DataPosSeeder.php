<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriMenu;
use App\Models\Produk;
use App\Models\StokBahan;
use App\Models\Cabang;

class DataPosSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = Cabang::where('kode_cabang', 'mauteh_kanal')->first();

        // 1. Buat Kategori Menu
        $katTeh = KategoriMenu::create(['nama_kategori' => 'Teh']);
        $katSusu = KategoriMenu::create(['nama_kategori' => 'Susu']);
        $katKopi = KategoriMenu::create(['nama_kategori' => 'Kopi']);
        $katSyrup = KategoriMenu::create(['nama_kategori' => 'Syrup']);
        $katLainnya = KategoriMenu::create(['nama_kategori' => 'Lainnya']);

        // 2. Buat Produk (Menu)
        $produkData = [
            ['kategori_id' => $katTeh->id, 'kode_produk' => 'T1', 'nama_produk' => 'Jasmine Tea', 'harga' => 4000, 'gambar' => 'jasminetea.webp'],
            ['kategori_id' => $katTeh->id, 'kode_produk' => 'M2', 'nama_produk' => 'Teh Kampul', 'harga' => 5000, 'gambar' => 'teh kampul.webp'],
            ['kategori_id' => $katSusu->id, 'kode_produk' => 'M_Susu', 'nama_produk' => 'Susu', 'harga' => 8000, 'gambar' => 'susu.webp'],
            ['kategori_id' => $katKopi->id, 'kode_produk' => 'M17', 'nama_produk' => 'Coffee Susu', 'harga' => 10000, 'gambar' => 'kopisusu.webp'],
            ['kategori_id' => $katLainnya->id, 'kode_produk' => 'M37', 'nama_produk' => 'Custom Menu (Manual)', 'harga' => 0, 'gambar' => null, 'is_custom' => true],
        ];
        foreach ($produkData as $produk) {
            Produk::create($produk);
        }

        // 3. Buat Stok Bahan Baku Fisik
        $stokData = [
            ['kode_bahan' => 'cup', 'nama_bahan' => 'Cup', 'jumlah_stok' => 500, 'satuan' => 'Pcs'],
            ['kode_bahan' => 'es_kristal', 'nama_bahan' => 'Es Kristal', 'jumlah_stok' => 20, 'satuan' => 'Ball'],
            ['kode_bahan' => 'b_coffesusu', 'nama_bahan' => 'Bubuk Coffee Susu', 'jumlah_stok' => 10, 'satuan' => 'Pack'],
            ['kode_bahan' => 'uht', 'nama_bahan' => 'Susu UHT', 'jumlah_stok' => 999, 'satuan' => 'Status'], // 999 sebagai penanda "Masih"
        ];
        foreach ($stokData as $stok) {
            StokBahan::create([
                'cabang_id' => $cabang->id,
                'kode_bahan' => $stok['kode_bahan'],
                'nama_bahan' => $stok['nama_bahan'],
                'jumlah_stok' => $stok['jumlah_stok'],
                'satuan' => $stok['satuan']
            ]);
        }
    }
}