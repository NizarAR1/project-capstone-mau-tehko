<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->integer('cabang_id');
            $table->date('tanggal');
            $table->string('nama_pengeluaran'); // Contoh: "Tarikan Belanja"
            $table->integer('nominal');         // Contoh: 150000
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};