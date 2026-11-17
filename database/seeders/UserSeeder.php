<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        $cabang = Cabang::create([
            'kode_cabang' => 'mauteh_kanal',
            'nama_cabang' => 'Mau Teh - Kanal',
            'saldo_stand' => 0,
            'tabungan_wajib' => 0
        ]);

        // Akun Leader (Owner)
        User::create([
            'name' => 'Owner System',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin'), // Password otomatis dienkripsi (Bcrypt)
            'role' => 'leader',
            'cabang_id' => $cabang->id
        ]);

        // Akun Kasir
        User::create([
            'name' => 'Kasir Kanal',
            'email' => 'mauteh_kanal@pos.com',
            'password' => Hash::make('123'),
            'role' => 'kasir',
            'cabang_id' => $cabang->id
        ]);
    }
}