<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Produk;
use App\Models\KategoriMenu;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\StokBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    // 1. Menampilkan Halaman Kasir / Dashboard
    public function index()
    {
        // =======================================================
        // 1. JIKA BELUM LOGIN, LANGSUNG TAMPILKAN HALAMAN LOGIN
        // =======================================================
        if (!auth()->check()) {
            return view('pos.index');
        }

        // =======================================================
        // 2. JIKA SUDAH LOGIN, JALANKAN MESIN KASIR/DASHBOARD
        // =======================================================
        $user = auth()->user();
        
        $semuaCabang = Cabang::all();
        $kategoriList = KategoriMenu::all();
        $produkList = Produk::all();

        // Cek apakah yang login adalah owner atau kasir cabang
        if ($user->name === 'owner_pusat') {
            $cabangId = request('cabang_id', 1); 
            $cabang = Cabang::find($cabangId);
            if (!$cabang) $cabang = Cabang::find(1);

            $stokList = StokBahan::where('cabang_id', $cabang->id)->get();
            $pengeluaranGlobal = \App\Models\Pengeluaran::orderBy('created_at', 'desc')->take(50)->get();
            $mutasiGlobal = \App\Models\LaporanHarian::orderBy('tanggal', 'desc')->take(50)->get();
        } else {
            // Jika yang login adalah kasir cabang
            $cabang = Cabang::where('kode_cabang', $user->name)->first();
            if (!$cabang) $cabang = Cabang::find(1); 
            
            $stokList = StokBahan::where('cabang_id', $cabang->id)->get();
            $pengeluaranGlobal = collect();
            $mutasiGlobal = collect();
        }

        // =======================================================
        // MESIN PENGHITUNG ANALISIS PENJUALAN (BERDASARKAN CABANG AKTIF)
        // =======================================================
        $totalPenjualanShift = Transaksi::where('cabang_id', $cabang->id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_tagihan');
            
        $cupTerjualShift = TransaksiDetail::whereHas('transaksi', function($q) use ($cabang) {
            $q->where('cabang_id', $cabang->id)->whereDate('created_at', now()->toDateString());
        })->sum('qty');

        // AMBIL DATA VARIAN APA SAJA YANG LAKU HARI INI
        $rincianTerjual = DB::table('transaksi_detail')
            ->selectRaw('produk.nama_produk, transaksi_detail.nama_custom, transaksi_detail.harga_satuan, SUM(transaksi_detail.qty) as total_qty, SUM(transaksi_detail.subtotal) as total_uang')
            ->leftJoin('produk', 'transaksi_detail.produk_id', '=', 'produk.id')
            ->join('transaksi', 'transaksi_detail.transaksi_id', '=', 'transaksi.id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->whereDate('transaksi.created_at', now()->toDateString())
            ->groupBy('produk.nama_produk', 'transaksi_detail.nama_custom', 'transaksi_detail.harga_satuan')
            ->orderByDesc('total_qty')
            ->get();

        // =======================================================
        // DATA CHART / GRAFIK
        // =======================================================
        $dataGrafikJam = [];
        $dataGrafikHarian = [];
        $labelGrafikHarian = [];

        // 1. Grafik per jam (Hari Ini)
        $grafikJam = TransaksiDetail::selectRaw('HOUR(transaksi.created_at) as jam, SUM(transaksi_detail.qty) as total_cup')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->whereDate('transaksi.created_at', now()->toDateString())
            ->groupBy('jam')
            ->get();
            
        for ($i = 8; $i <= 22; $i++) {
            $jamData = $grafikJam->firstWhere('jam', $i);
            $dataGrafikJam[] = $jamData ? (int)$jamData->total_cup : 0;
        }

        // 2. Grafik 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);
            $labelGrafikHarian[] = $tgl->isoFormat('dddd'); 
            
            $totalOmzet = Transaksi::whereDate('created_at', $tgl->toDateString())->sum('total_tagihan');
            $dataGrafikHarian[] = (int)$totalOmzet;
        }

        // 3. Grafik Leaderboard (Khusus Owner)
        $labelCabang = [];
        $dataOmzetCabang = [];
        $grandTotalRevenue = 0;
        $grandTotalCups = 0;
        $grandTotalExpense = 0;
        $grandTotalWajib = 0;

        $transaksiHariIni = Transaksi::whereDate('created_at', now()->toDateString())->get();

        foreach ($semuaCabang as $cb) {
            $labelCabang[] = $cb->nama_cabang;
            $omzetCabang = $transaksiHariIni->where('cabang_id', $cb->id)->sum('total_tagihan');
            $dataOmzetCabang[] = (int)$omzetCabang;
            
            $grandTotalRevenue += $omzetCabang;
            $grandTotalWajib += $cb->tabungan_wajib;
        }

        return view('pos.index', compact(
            'cabang', 
            'stokList', 
            'semuaCabang', 
            'kategoriList', 
            'produkList',   
            'pengeluaranGlobal',
            'mutasiGlobal',
            'dataGrafikJam',
            'dataGrafikHarian',
            'labelGrafikHarian',
            'labelCabang',
            'dataOmzetCabang',
            'grandTotalRevenue',
            'grandTotalCups',
            'grandTotalExpense',
            'grandTotalWajib',
            'transaksiHariIni',
            'totalPenjualanShift',
            'cupTerjualShift',
            'rincianTerjual'
        ));
    }

    // 2. Memproses Pembayaran / Checkout (Create)
    public function storeTransaksi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'total_tagihan'     => 'required|numeric',
            'uang_diterima'     => 'required|numeric',
            'kembalian'         => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'items'             => 'required|array',
            'items.*.id'        => 'required|string',
            'items.*.nama'      => 'required|string',
            'items.*.harga'     => 'required|numeric', 
            'items.*.qty'       => 'required|numeric',
            'items.*.bahan_terpakai' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap atau tidak valid.',
                'errors'  => $validator->errors()
            ], 422); 
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $cabang = Cabang::where('kode_cabang', $user->name)->first();
            
            $userId = $user->id;
            $cabangId = $cabang ? $cabang->id : 1;

            $transaksi = new Transaksi();
            $transaksi->cabang_id = $cabangId;
            $transaksi->user_id = $userId;
            $transaksi->total_tagihan = $request->total_tagihan;
            $transaksi->uang_diterima = $request->uang_diterima;           
            $transaksi->save();

            foreach ($request->items as $item) {
                $detail = new TransaksiDetail();
                $detail->transaksi_id = $transaksi->id;
                $detail->qty = $item['qty'];
                $detail->harga_satuan = $item['harga']; 
                $detail->subtotal = $item['qty'] * $item['harga'];

                if (isset($item['is_custom']) && $item['is_custom'] == true) {
                    $detail->nama_custom = $item['nama'];
                    $detail->produk_id = null;
                } else {
                    $produkDb = Produk::where('kode_produk', $item['id'])->first();
                    if ($produkDb) {
                        $detail->produk_id = $produkDb->id;
                    }
                    $detail->nama_custom = null;
                }
                $detail->save();

                // Pemotongan Stok Otomatis
                if (isset($item['bahan_terpakai']) && is_array($item['bahan_terpakai'])) {
                    foreach ($item['bahan_terpakai'] as $kodeBahan) {
                        $stokDb = StokBahan::where('cabang_id', $cabangId)
                                    ->where('kode_bahan', $kodeBahan) 
                                    ->first();

                        if ($stokDb) {
                            $stokDb->jumlah_stok = $stokDb->jumlah_stok - $item['qty'];
                            $stokDb->save();
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan ke MySQL!',
                'transaksi_id' => $transaksi->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // 3. Menyimpan Laporan Harian & Pengeluaran
    public function simpanLaporan(Request $request)
    {
        try {
            $cabang = Cabang::where('kode_cabang', auth()->user()->name)->first();
            $cabangId = $cabang ? $cabang->id : 1; 
            $tanggalHariIni = now()->toDateString();
            
            $uangLaci = $request->uang_laci ?? 0;
            $uangQris = $request->uang_qris ?? 0;

            $laporan = DB::table('laporan_harians')
                ->where('cabang_id', $cabangId)
                ->where('tanggal', $tanggalHariIni)
                ->first();

            if ($laporan) {
                DB::table('laporan_harians')->where('id', $laporan->id)->update([
                    'uang_laci' => $uangLaci,
                    'uang_qris' => $uangQris,
                    'updated_at' => now()
                ]);
            } else {
                DB::table('laporan_harians')->insert([
                    'cabang_id' => $cabangId,
                    'tanggal' => $tanggalHariIni,
                    'uang_laci' => $uangLaci,
                    'uang_qris' => $uangQris,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::table('pengeluarans')
                ->where('cabang_id', $cabangId)
                ->where('tanggal', $tanggalHariIni)
                ->delete();

            if ($request->has('pengeluaran') && is_array($request->pengeluaran)) {
                foreach ($request->pengeluaran as $peng) {
                    DB::table('pengeluarans')->insert([
                        'cabang_id' => $cabangId,
                        'tanggal' => $tanggalHariIni,
                        'nama_pengeluaran' => $peng['nama'],
                        'nominal' => $peng['nominal'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Laporan Tutup Shift berhasil disimpan ke MySQL!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 4. Memperbarui Stok Bahan Baku secara Manual
    public function updateStok(Request $request)
    {
        $request->validate([
            'stok_data'   => 'required|array',
            'stok_data.*.id' => 'required|integer',
            'stok_data.*.jumlah' => 'required|numeric'
        ]);

        try {
            foreach ($request->stok_data as $item) {
                $stok = StokBahan::find($item['id']);
                if ($stok) {
                    $stok->jumlah_stok = $item['jumlah'];
                    $stok->save();
                }
            }

            return response()->json([
                'success' => true, 
                'message' => 'Data stok fisik berhasil disesuaikan di sistem!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyesuaikan stok: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // SISTEM LOGIN & LOGOUT LARAVEL
    // ==========================================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/'); 
        }

        return back()->withErrors([
            'login' => 'Username atau password Anda salah!',
        ]);
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}