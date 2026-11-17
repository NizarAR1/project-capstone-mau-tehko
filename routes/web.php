<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AuthController;

Route::get('/', [PosController::class, 'index'])->name('home');

// Rute Autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/checkout', [PosController::class, 'storeTransaksi']);
Route::post('/simpan-laporan', [\App\Http\Controllers\PosController::class, 'simpanLaporan']);
Route::post('/login', [\App\Http\Controllers\PosController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\PosController::class, 'logout']);
Route::post('/update-stok', [\App\Http\Controllers\PosController::class, 'updateStok']);

Route::get('/generate-users', function () {
    // 1. Buat Akun Khusus Owner / Bos Pusat
    \App\Models\User::updateOrCreate(
        ['name' => 'owner_pusat'], // Username untuk Anda
        [
            'email' => 'owner@mauteh.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123') // Password Anda
        ]
    );

    // 2. Looping: Buat akun untuk SEMUA cabang otomatis!
    $semuaCabang = \App\Models\Cabang::all();
    $jumlah = 0;
    
    foreach ($semuaCabang as $cabang) {
        \App\Models\User::updateOrCreate(
            ['name' => $cabang->kode_cabang], 
            [
                'email' => $cabang->kode_cabang . '@mauteh.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123') // Password standar kasir
            ]
        );
        $jumlah++;
    }

    return "Selesai! Akun Owner dan $jumlah Akun Cabang berhasil disiapkan.";
});

Route::get('/generate-menu', function () {
    // 1. Matikan perlindungan Foreign Key sementara
    \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    
    // Kosongkan data menu lama
    \App\Models\Produk::truncate();
    
    // Nyalakan kembali perlindungan Foreign Key
    \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    
    // 2. Kategori Tetap
    \App\Models\KategoriMenu::updateOrCreate(['id' => 1], ['nama_kategori' => 'Teh']);
    \App\Models\KategoriMenu::updateOrCreate(['id' => 2], ['nama_kategori' => 'Susu']);
    \App\Models\KategoriMenu::updateOrCreate(['id' => 3], ['nama_kategori' => 'Kopi']);
    \App\Models\KategoriMenu::updateOrCreate(['id' => 4], ['nama_kategori' => 'Syrup']);
    \App\Models\KategoriMenu::updateOrCreate(['id' => 5], ['nama_kategori' => 'Lainnya']);

    // 3. Daftar Lengkap 40+ Menu (Persis seperti script.js lama)
    $produkLengkap = [
        // --- KATEGORI TEH ---
        ['kode' => 'T1', 'nama' => 'Jasmine Tea', 'harga' => 4000, 'kat' => 1, 'img' => 'jasminetea.webp'],
        ['kode' => 'M2', 'nama' => 'Teh Kampul', 'harga' => 5000, 'kat' => 1, 'img' => 'teh kampul.webp'],
        ['kode' => 'M3', 'nama' => 'Apel Tea', 'harga' => 6000, 'kat' => 1, 'img' => 'apeltea.webp'],
        ['kode' => 'M4', 'nama' => 'Blackcurrant Tea', 'harga' => 6000, 'kat' => 1, 'img' => 'baclcurranttea.webp'],
        ['kode' => 'M5', 'nama' => 'Lychee Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'lecytea.webp'],
        ['kode' => 'M6', 'nama' => 'Lemon Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'lemontea.webp'],
        ['kode' => 'M7', 'nama' => 'Blueberry Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'blueberrytea.webp'],
        ['kode' => 'M8', 'nama' => 'Kiwi Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'kiwitea.webp'],
        ['kode' => 'M9', 'nama' => 'Manggo Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'mangotea.webp'],
        ['kode' => 'M10', 'nama' => 'Jahe Merah Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'jahemerahtea.webp'],
        ['kode' => 'M11', 'nama' => 'Thai Tea', 'harga' => 7000, 'kat' => 1, 'img' => 'thaitea.webp'],
        ['kode' => 'M_Jeruk', 'nama' => 'Jeruk Peras', 'harga' => 7000, 'kat' => 1, 'img' => 'jerukperas.webp'],

        // --- KATEGORI SUSU ---
        ['kode' => 'M_Susu', 'nama' => 'Susu', 'harga' => 8000, 'kat' => 2, 'img' => 'susu.webp'],
        ['kode' => 'M12', 'nama' => 'Green Tea', 'harga' => 10000, 'kat' => 2, 'img' => 'greentea.webp'],
        ['kode' => 'M13', 'nama' => 'Taro', 'harga' => 10000, 'kat' => 2, 'img' => 'taro.webp'],
        ['kode' => 'M14', 'nama' => 'Coklat', 'harga' => 10000, 'kat' => 2, 'img' => 'coklat.webp'],
        ['kode' => 'M15', 'nama' => 'Redvelvet', 'harga' => 10000, 'kat' => 2, 'img' => 'redvelvet.webp'],
        ['kode' => 'M16', 'nama' => 'Oreo', 'harga' => 10000, 'kat' => 2, 'img' => 'oreo.webp'],
        ['kode' => 'T22', 'nama' => 'Klepon', 'harga' => 10000, 'kat' => 2, 'img' => 'klepon.webp'], // Dari Tehko

        // --- KATEGORI KOPI ---
        ['kode' => 'M17', 'nama' => 'Coffee Susu', 'harga' => 10000, 'kat' => 3, 'img' => 'kopisusu.webp'],
        ['kode' => 'M_Espresso', 'nama' => 'Coffee Espresso', 'harga' => 10000, 'kat' => 3, 'img' => 'ekspresso.webp'],
        ['kode' => 'M18', 'nama' => 'Coffee Gula Aren', 'harga' => 10000, 'kat' => 3, 'img' => 'gulaaren.webp'],
        ['kode' => 'M19', 'nama' => 'Coffee Cappuccino', 'harga' => 12000, 'kat' => 3, 'img' => 'cappucino.webp'],
        ['kode' => 'M20', 'nama' => 'Coffee Butterscotch', 'harga' => 12000, 'kat' => 3, 'img' => 'butterschot.webp'],
        ['kode' => 'M21', 'nama' => 'Coffee Pandan', 'harga' => 12000, 'kat' => 3, 'img' => 'pandan.webp'],
        ['kode' => 'T15', 'nama' => 'Kopsu Avocado', 'harga' => 12000, 'kat' => 3, 'img' => 'avocado.webp'], // Dari Tehko

        // --- KATEGORI SYRUP ---
        ['kode' => 'M22', 'nama' => 'Blueberry Syrup', 'harga' => 6000, 'kat' => 4, 'img' => 'blueberrysyrup.webp'],
        ['kode' => 'M23', 'nama' => 'Strawberry Syrup', 'harga' => 6000, 'kat' => 4, 'img' => 'strawberrysyrup.webp'],
        ['kode' => 'M24', 'nama' => 'Melon Syrup', 'harga' => 6000, 'kat' => 4, 'img' => 'melonsyrup.webp'],
        ['kode' => 'M25', 'nama' => 'Orange Syrup', 'harga' => 6000, 'kat' => 4, 'img' => 'orangesyrup.webp'],
        ['kode' => 'M_BSusu', 'nama' => 'Blueberry Susu', 'harga' => 8000, 'kat' => 4, 'img' => 'blueberrysusu.webp'],
        ['kode' => 'M_SSusu', 'nama' => 'Strawberry Susu', 'harga' => 8000, 'kat' => 4, 'img' => 'strawberrysusu.webp'],
        ['kode' => 'M_MSusu', 'nama' => 'Melon Susu', 'harga' => 8000, 'kat' => 4, 'img' => 'melonsusu.webp'],
        ['kode' => 'M_OSusu', 'nama' => 'Orange Susu', 'harga' => 8000, 'kat' => 4, 'img' => 'orangesusu.webp'],
        ['kode' => 'M28', 'nama' => 'Blueberry Yakult', 'harga' => 8000, 'kat' => 4, 'img' => 'blueberryyakult.webp'],
        ['kode' => 'M29', 'nama' => 'Strawberry Yakult', 'harga' => 8000, 'kat' => 4, 'img' => 'strawberryyakult.webp'],
        ['kode' => 'M26', 'nama' => 'Orange Yakult', 'harga' => 8000, 'kat' => 4, 'img' => 'orangeyakult.webp'],
        ['kode' => 'M27', 'nama' => 'Melon Yakult', 'harga' => 8000, 'kat' => 4, 'img' => 'melonyakult.webp'],
        ['kode' => 'M30', 'nama' => 'Blueberry Sprite', 'harga' => 8000, 'kat' => 4, 'img' => 'blueberrysprite.webp'],
        ['kode' => 'M31', 'nama' => 'Strawberry Sprite', 'harga' => 8000, 'kat' => 4, 'img' => 'strawberrysprite.webp'],
        ['kode' => 'M32', 'nama' => 'Melon Sprite', 'harga' => 8000, 'kat' => 4, 'img' => 'melonsprite.webp'],
        ['kode' => 'M33', 'nama' => 'Orange Sprite', 'harga' => 8000, 'kat' => 4, 'img' => 'orangesprite.webp'],

        // --- KATEGORI LAINNYA ---
        ['kode' => 'M34', 'nama' => 'Cup Gratis & Mas Yayan', 'harga' => 0, 'kat' => 5, 'img' => null],
        ['kode' => 'M35', 'nama' => 'Cup Keluar (Rusak)', 'harga' => 0, 'kat' => 5, 'img' => null],
        ['kode' => 'M36', 'nama' => 'Es Kristal', 'harga' => 2500, 'kat' => 5, 'img' => null],
        ['kode' => 'M37', 'nama' => 'Custom Menu (Manual)', 'harga' => 0, 'kat' => 5, 'img' => null],
    ];

    $jumlah = 0;
    foreach ($produkLengkap as $p) {
        \App\Models\Produk::updateOrCreate(
            ['kode_produk' => $p['kode']],
            [
                'kategori_id' => $p['kat'],
                'nama_produk' => $p['nama'],
                'harga'       => $p['harga'],
                'gambar'      => $p['img']
            ]
        );
        $jumlah++;
    }

    return "Selesai! Total $jumlah menu (termasuk Klepon & Avocado) berhasil disinkronkan persis seperti script.js lama!";
});

use Illuminate\Support\Facades\Auth;

// Rute Logout Anti-Error (Mendukung Klik Tombol & Ketik URL)
Route::any('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});