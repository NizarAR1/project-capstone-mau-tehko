@extends('layouts.app')
@section('title', 'Mau Teh-ko | Modern POS')

@section('content')
<!-- ========================================== -->
<!-- GLOBAL UI STYLES (MODERN SAAS THEME)       -->
<!-- ========================================== -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary: #059669; /* Emerald 600 */
        --primary-hover: #047857; /* Emerald 700 */
        --secondary: #f59e0b; /* Amber 500 */
        --bg-app: #f8fafc; /* Slate 50 */
        --bg-card: #ffffff;
        --text-main: #0f172a; /* Slate 900 */
        --text-muted: #64748b; /* Slate 500 */
        --border-color: #e2e8f0; /* Slate 200 */
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        --shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.08);
    }

    * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
    body { background-color: var(--bg-app); color: var(--text-main); margin: 0; }

    /* Login Screen Modern */
    .login-container { display: flex; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #059669 0%, #0f172a 100%); }
    .login-box { background: var(--bg-card); padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); width: 100%; max-width: 400px; text-align: center; }
    .login-box h2 { font-weight: 800; color: var(--text-main); margin-bottom: 30px; font-size: 24px; }
    .login-input { width: 100%; padding: 14px 16px; margin-bottom: 15px; border: 1.5px solid var(--border-color); border-radius: 8px; font-size: 15px; transition: all 0.3s; background: var(--bg-app); }
    .login-input:focus { border-color: var(--primary); outline: none; background: white; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }
    .btn-login { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-login:hover { background: var(--primary-hover); transform: translateY(-2px); }

    /* Layout Utama */
    .main-content { padding: 25px; flex: 1; overflow-y: auto; background-color: var(--bg-app); }
    .tab-content { display: none; animation: fadeIn 0.4s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Header & Filter Kasir */
    .search-bar { width: 100%; padding: 14px 20px; font-size: 15px; border: 1.5px solid var(--border-color); border-radius: var(--radius-md); outline: none; transition: 0.3s; box-shadow: var(--shadow-sm); }
    .search-bar:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }
    
    .category-filters { display: flex; gap: 10px; overflow-x: auto; padding: 5px 0 15px 0; margin-bottom: 10px; }
    .category-filters::-webkit-scrollbar { height: 4px; }
    .category-filters::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .filter-btn { padding: 8px 20px; background: white; border: 1px solid var(--border-color); color: var(--text-muted); border-radius: 20px; font-weight: 600; font-size: 14px; cursor: pointer; transition: 0.3s; white-space: nowrap; }
    .filter-btn:hover { border-color: var(--primary); color: var(--primary); }
    .filter-btn.active { background: var(--primary); color: white; border-color: var(--primary); box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2); }

    /* POS Grid & Produk */
    .pos-container { display: flex; gap: 25px; align-items: flex-start; flex-wrap: wrap; }
    .menu-list { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; }
    
    .menu-item { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 15px; cursor: pointer; position: relative; transition: all 0.3s ease; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; min-height: 120px; overflow: hidden; }
    .menu-item:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); border-color: var(--primary); }
    .menu-item h3 { margin: 0 0 8px 0; font-size: 15px; font-weight: 700; color: var(--text-main); position: relative; z-index: 2; }
    .menu-item .price { margin: 0; font-size: 14px; font-weight: 600; color: var(--primary); position: relative; z-index: 2; }
    .menu-item img { position: absolute; right: -15px; bottom: -15px; width: 90px; height: 90px; object-fit: contain; transform: rotate(15deg); opacity: 0.9; transition: 0.3s; }
    .menu-item:hover img { transform: rotate(0deg) scale(1.1); opacity: 1; }

    /* Keranjang */
    .cart { width: 320px; background: var(--bg-card); border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-md); position: sticky; top: 20px; border: 1px solid var(--border-color); }
    .cart h3 { margin-top: 0; font-size: 18px; font-weight: 800; border-bottom: 2px dashed var(--border-color); padding-bottom: 12px; margin-bottom: 15px; }
    #cart-items { list-style: none; padding: 0; margin: 0 0 15px 0; max-height: 350px; overflow-y: auto; }
    #cart-items li { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 500; }
    .cart .total { font-size: 20px; font-weight: 800; color: var(--primary); display: flex; justify-content: space-between; margin-bottom: 20px; padding-top: 10px; border-top: 2px solid var(--border-color); }
    .btn-checkout { width: 100%; padding: 15px; background: var(--primary); color: white; border: none; border-radius: var(--radius-md); font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2); }
    .btn-checkout:hover { background: var(--primary-hover); transform: translateY(-2px); }

    /* Global Cards & Tables untuk Laporan/Dashboard */
    .modern-card { background: var(--bg-card); border-radius: var(--radius-lg); padding: 25px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); margin-bottom: 25px; }
    .modern-card h2, .modern-card h3 { margin-top: 0; color: var(--text-main); font-weight: 800; }
    
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th { background-color: #f8fafc; color: var(--text-muted); font-size: 13px; font-weight: 700; text-transform: uppercase; padding: 15px 20px; border-bottom: 2px solid var(--border-color); }
    .data-table td { padding: 15px 20px; font-size: 14px; border-bottom: 1px solid #f1f5f9; color: var(--text-main); font-weight: 500; }
    .data-table tbody tr:hover { background-color: #f8fafc; }

    /* Tombol-tombol Modern */
    .btn-modern { padding: 12px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-success { background: var(--primary); color: white; box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2); }
    .btn-success:hover { background: var(--primary-hover); }
    .btn-warning { background: var(--secondary); color: white; }
    .btn-warning:hover { background: #d97706; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2826; }

    /* Input Laporan Modern */
    .input-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
    .rekap-input { width: 100%; padding: 12px 16px; border: 1.5px solid var(--border-color); border-radius: 8px; font-size: 15px; font-weight: 600; background: #f8fafc; transition: 0.3s; }
    .rekap-input:focus { border-color: var(--primary); background: white; outline: none; }
</style>

{{-- TAMPILAN JIKA BELUM LOGIN --}}
@guest
<div id="login-screen" class="login-container">
    <form method="POST" action="/login" class="login-box">
        @csrf
        <h2>Portal Karyawan</h2>
        
        <input type="text" name="username" placeholder="Username (Cth: mauteh_kanal)" class="login-input" required />
        <input type="password" name="password" id="password" placeholder="Password" class="login-input" required />

        <div style="text-align: right; margin-bottom: 20px;">
            <button type="button" id="toggle-password" onclick="togglePassword()" style="background: none; border: none; font-size: 13px; color: var(--text-muted); cursor: pointer; font-weight: 600; outline: none;">
                🙈 Lihat Password
            </button>
        </div>

        <button type="submit" class="btn-login">Masuk Aplikasi</button>
        
        @error('login')
            <p style="color: #ef4444; margin-top: 15px; font-size: 14px; font-weight: 500;">{{ $message }}</p>
        @enderror
    </form>
</div>
@endguest

{{-- TAMPILAN JIKA SUDAH LOGIN --}}
@auth
<div id="main-app" style="display: flex; width: 100%; height: 100vh; overflow: hidden;">
    
    <!-- MEMANGGIL SIDEBAR -->
    @include('partials.sidebar')

    <div class="main-content">
        <!-- MEMANGGIL TOPBAR -->
        @include('partials.topbar')

        <!-- ============================================== -->
        <!-- TAB 1: KASIR (POS)                             -->
        <!-- ============================================== -->
        <div id="pos" class="tab-content active">
            <div style="margin-bottom: 20px;">
                <input type="text" id="search-input" placeholder="🔍 Cari menu..." onkeyup="searchMenu()" class="search-bar"/>
            </div>

            <!-- Filter Kategori -->
            <div class="category-filters">
                <button class="filter-btn active" onclick="filterCategory('semua', this)">Semua Menu</button>
                @foreach ($kategoriList as $kategori)
                    <button class="filter-btn" onclick="filterCategory('{{ strtolower($kategori->nama_kategori) }}', this)">
                        {{ $kategori->nama_kategori }}
                    </button>
                @endforeach
            </div>

            <div class="pos-container">
                <!-- List Produk -->
                <div class="menu-list" id="menu-list">
                    @if (count($produkList) === 0)
                        <div style="grid-column: 1/-1; padding: 30px; text-align: center; background: white; border-radius: 12px; color: #ef4444; font-weight: bold;">Data menu belum dimasukkan ke database.</div>
                    @else
                        @foreach($produkList as $produk)
                            <div class="menu-item" onclick="tambahKeKeranjang('{{ $produk->kode_produk }}', '{{ $produk->nama_produk }}', {{ $produk->harga }})" data-kategori="{{ $produk->kategori_id }}">
                                <div>
                                    <h3>{{ $produk->nama_produk }}</h3>
                                    <p class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                </div>
                                @if($produk->gambar)
                                    <img src="{{ asset('assets/img/' . $produk->gambar) }}" loading="lazy" alt="img">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Keranjang -->
                <div class="cart">
                    <h3>Keranjang <span id="cart-store-label" style="font-size: 14px; color: var(--text-muted); float: right;"></span></h3>
                    <ul id="cart-items">
                        <!-- Item masuk sini via JS -->
                    </ul>
                    <div class="total">
                        <span>Total:</span>
                        <span>Rp <span id="total-price">0</span></span>
                    </div>
                    <button class="btn-checkout" onclick="checkout()">🛒 Proses Bayar</button>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: STOK BAHAN BAKU                         -->
        <!-- ============================================== -->
        <div id="stok" class="tab-content">
            <div class="modern-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2>📦 Stock Bahan Baku <span style="color: var(--primary); font-size: 18px; font-weight: 600;">- {{ $cabang->nama_cabang ?? 'Pilih Cabang' }}</span></h2>
                        <p style="color: var(--text-muted); margin-bottom: 0; font-size: 14px;">Sesuaikan fisik barang di stand dengan sistem.</p>
                    </div>
                    
                    <!-- TOMBOL PILIH CABANG KHUSUS OWNER -->
                    @if(auth()->user()->name === 'owner_pusat')
                    <div>
                        <form action="/" method="GET" id="form-cabang" style="margin: 0;">
                            <select name="cabang_id" onchange="document.getElementById('form-cabang').submit()" style="padding: 10px 15px; border-radius: 8px; border: 2px solid var(--primary); font-weight: bold; outline: none; background: #ecfdf5; color: #065f46; cursor: pointer; box-shadow: 0 2px 4px rgba(5,150,105,0.1);">
                                @foreach($semuaCabang as $cb)
                                    <option value="{{ $cb->id }}" {{ request('cabang_id') == $cb->id ? 'selected' : '' }}>🏢 {{ $cb->nama_cabang }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tab" value="stok">
                        </form>
                    </div>
                    @endif
                </div>
                <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="text-align: center;">Sisa Sistem</th>
                                <th style="text-align: center;">Input Fisik Asli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokList as $stok)
                            <tr>
                                <td style="font-weight: 700; color: var(--text-main);">{{ $stok->nama_bahan }}</td>
                                <td style="text-align: center; font-weight: 700;">
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 20px; font-size: 13px;">{{ $stok->jumlah_stok }} {{ $stok->satuan }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" class="input-stok-bahan rekap-input" data-id="{{ $stok->id }}" value="{{ $stok->jumlah_stok }}" style="width: 100px; text-align: center; padding: 8px;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button class="btn-modern btn-warning" onclick="simpanStokLaravel(this)" style="width: 100%; margin-top: 20px; padding: 15px; justify-content: center; font-size: 16px;">
                    💾 Simpan Penyesuaian Stok
                </button>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TAB 3: DASHBOARD PUSAT (KHUSUS LEADER)         -->
        <!-- ============================================== -->
        <div id="dashboard-pusat" class="tab-content leader-only">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
            <div>
              <h2 style="font-size: 24px; font-weight: 800; margin: 0; color: var(--text-main);">👑 Ruang Kendali Pusat</h2>
              <p style="color: var(--text-muted); margin: 4px 0 0 0; font-size: 14px;">Pantauan omzet, laba bersih, dan arus kas 9 cabang secara real-time.</p>
            </div>
            <button class="btn-modern btn-success" onclick="downloadDataAnalisisExcel()">📊 Export Laporan Excel</button>
          </div>

          <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 15px;">
            <button class="filter-btn sub-btn active" onclick="switchSubMenuPusat('sub-overview', this)">📊 Ringkasan & Laba</button>
            <button class="filter-btn sub-btn" onclick="switchSubMenuPusat('sub-keuangan', this)">💸 Arus Kas Laci</button>
          </div>

          <!-- WADAH 1: RINGKASAN & LABA -->
          <div id="sub-overview" class="sub-content-pusat" style="display: block;">
            
            <div class="modern-card">
              <h3 style="margin-bottom: 20px;">📈 Peringkat Omzet Cabang Hari Ini</h3>
              <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="leaderboardChart"></canvas>
              </div>
            </div>

            <!-- WADAH CARD LABA & ASET DIBIARKAN SAMA (Hanya ganti bungkus class modern-card) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <div class="modern-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="padding: 20px; display: flex; gap: 15px;">
                        <div style="flex: 1; background: #f8fafc; padding: 15px; border-radius: 12px;">
                            <span style="font-size: 12px; font-weight: bold; color: var(--text-muted);">TOTAL OMZET</span>
                            <div style="color: var(--primary); font-size: 24px; font-weight: 800;">Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}</div>
                            <span style="font-size: 12px; color: var(--text-muted);">{{ $grandTotalCups }} Cup Terjual</span>
                        </div>
                        <div style="flex: 1; background: #fef2f2; padding: 15px; border-radius: 12px;">
                            <span style="font-size: 12px; font-weight: bold; color: var(--text-muted);">PENGELUARAN</span>
                            <div style="color: #dc2626; font-size: 24px; font-weight: 800;">Rp {{ number_format($grandTotalExpense, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div style="background: #ecfdf5; padding: 15px 20px; display: flex; justify-content: space-between; border-top: 1px solid #d1fae5; margin-top: auto;">
                        <span style="font-weight: 800; color: var(--primary);">LABA KOTOR</span>
                        <span style="font-weight: 900; color: var(--primary); font-size: 20px;">Rp {{ number_format($grandTotalRevenue - $grandTotalExpense, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="modern-card" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; display: flex; flex-direction: column; justify-content: center;">
                    <div style="font-size: 13px; font-weight: 700; color: #94a3b8; margin-bottom: 8px;">🏦 TOTAL SALDO TABUNGAN (ASET)</div>
                    <div style="font-size: 32px; font-weight: 900;">Rp {{ number_format($grandTotalWajib, 0, ',', '.') }}</div>
                </div>
            </div>
          </div>

          <!-- WADAH 2: ARUS KAS LACI -->
          <div id="sub-keuangan" class="sub-content-pusat" style="display: none;">
            <div class="modern-card">
              <h3 style="color: var(--text-main); margin-bottom: 20px;">📘 Buku Mutasi Tabungan Wajib (Tutup Buku)</h3>
              <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                <table class="data-table">
                  <thead><tr><th>Tanggal</th><th>Cabang</th><th>Total Uang Asli</th><th>Pengeluaran</th><th>Tabungan Wajib</th></tr></thead>
                  <tbody>
                    @forelse($mutasiGlobal as $mutasi)
                      @php $namaCb = \App\Models\Cabang::find($mutasi->cabang_id)->nama_cabang ?? 'Tidak diketahui'; @endphp
                      <tr>
                        <td>{{ \Carbon\Carbon::parse($mutasi->tanggal)->format('d M Y') }}</td>
                        <td style="font-weight: bold; color: #0284c7;">{{ $namaCb }}</td>
                        <td>Rp {{ number_format($mutasi->total_uang_asli, 0, ',', '.') }}</td>
                        <td style="color: #ef4444;">Rp {{ number_format($mutasi->total_pengeluaran, 0, ',', '.') }}</td>
                        <td style="color: var(--primary); font-weight: 800;">Rp {{ number_format($mutasi->tabungan_wajib, 0, ',', '.') }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada data rekap tutup buku...</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Tambahan Tabel Pengeluaran di bawahnya bisa dipasang di sini mengikuti struktur atas -->
          </div>
        </div>

       <!-- ============================================== -->
        <!-- TAB 4: LAPORAN & ANALISIS PENJUALAN KARYAWAN   -->
        <!-- ============================================== -->
        <div id="laporan" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0; font-size: 24px; font-weight: 800;">📊 Analisis Penjualan & Tutup Kasir</h2>
                    <p style="color: var(--text-muted); margin: 5px 0 0 0; font-size: 14px;">Cabang: <strong style="color: var(--primary);">{{ $cabang->nama_cabang }}</strong> | Tanggal: <strong style="color: var(--text-main);">{{ \Carbon\Carbon::now()->format('d M Y') }}</strong></p>
                </div>
                
                <!-- TOMBOL PILIH CABANG KHUSUS OWNER -->
                @if(auth()->user()->name === 'owner_pusat')
                <div>
                    <form action="/" method="GET" id="form-cabang-laporan" style="margin: 0;">
                        <select name="cabang_id" onchange="document.getElementById('form-cabang-laporan').submit()" style="padding: 10px 15px; border-radius: 8px; border: 2px solid var(--primary); font-weight: bold; outline: none; background: #ecfdf5; color: #065f46; cursor: pointer;">
                            @foreach($semuaCabang as $cb)
                                <option value="{{ $cb->id }}" {{ request('cabang_id') == $cb->id ? 'selected' : '' }}>🏢 {{ $cb->nama_cabang }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="tab" value="laporan">
                    </form>
                </div>
                @endif
            </div>

            <!-- WADAH PENDAPATAN SISTEM -->
            <div class="modern-card" style="margin-bottom: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <span style="font-size: 13px; color: #166534; font-weight: bold;">TOTAL PENDAPATAN DI SISTEM HARI INI</span>
                    <div style="font-size: 28px; font-weight: 900; color: #15803d;">Rp <span id="rekap-total-penjualan">{{ number_format($totalPenjualanShift ?? 0, 0, ',', '.') }}</span></div>
                    <span style="font-size: 12px; color: #166534;">*Uang fisik di laci harus seimbang dengan angka ini</span>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 13px; color: #166534; font-weight: bold;">CUP TERJUAL</span>
                    <div style="font-size: 28px; font-weight: 900; color: #15803d;">{{ $cupTerjualShift ?? 0 }} <span style="font-size: 18px;">Pcs</span></div>
                </div>
            </div>

            <!-- TABEL VARIAN TERJUAL HARI INI -->
            <div class="modern-card" style="margin-bottom: 20px;">
                <h3 style="margin-top:0; margin-bottom:15px;">🏆 Daftar Varian Terjual Hari Ini</h3>
                <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color);">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Menu / Varian</th>
                                <th style="text-align:center;">Harga Satuan</th>
                                <th style="text-align:center;">Cup Terjual</th>
                                <th style="text-align:right;">Total Uang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rincianTerjual as $item)
                                <tr>
                                    <td style="font-weight:bold; color: var(--text-main);">{{ $item->nama_produk ?? $item->nama_custom }}</td>
                                    <td style="text-align:center;">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td style="text-align:center; color: var(--primary); font-weight:bold; font-size:16px;">{{ $item->total_qty }}</td>
                                    <td style="text-align:right; font-weight:bold;">Rp {{ number_format($item->total_uang, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding: 20px; color: var(--text-muted);">Belum ada penjualan di cabang ini hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WADAH PENGELUARAN & UANG ASLI -->
            <div class="modern-card" style="background: white; border-top: 5px solid var(--primary);">
                <h3 style="margin-bottom: 5px;">Buku Kas Laci (Hitung Uang Asli)</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 25px;">Karyawan wajib mencatat pengeluaran harian dan menghitung uang fisik sebelum shift berakhir.</p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    
                    <!-- KOTAK KIRI: INPUT PENGELUARAN DINAMIS -->
                    <div style="background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        <h4 style="margin-top: 0; color: #ef4444; font-size: 15px; border-bottom: 2px dashed #fca5a5; padding-bottom: 10px;">📉 Catat Pengeluaran Laci</h4>
                        
                        <div id="pengeluaran-list" style="margin-bottom: 15px;"></div>
                        
                        @if(auth()->user()->name !== 'owner_pusat')
                        <button class="btn-modern btn-warning" onclick="addPengeluaranRow()" style="width: 100%; justify-content: center; padding: 10px; font-size: 13px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);">
                            ➕ Tambah Catatan Pengeluaran
                        </button>
                        @endif

                        <div style="display: flex; justify-content: space-between; font-size: 15px; background: #fee2e2; padding: 12px; border-radius: 6px; font-weight: bold; color: #b91c1c;">
                            <span>Total Pengeluaran:</span>
                            <span>Rp <span id="rekap-total-pengeluaran">0</span></span>
                        </div>
                    </div>

                    <!-- KOTAK KANAN: INPUT UANG LACI & ANALISIS SELISIH -->
                    <div style="background: #ecfdf5; padding: 20px; border-radius: var(--radius-md); border: 1px solid #d1fae5; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h4 style="margin-top: 0; color: var(--primary); font-size: 15px; border-bottom: 2px dashed #a7f3d0; padding-bottom: 10px;">💰 Evaluasi Uang Fisik</h4>
                            
                            <div class="input-group" style="margin-bottom: 15px;">
                                <label>Uang Asli di Laci (Kertas & Koin):</label>
                                <input type="text" inputmode="numeric" id="input-uang-laci" placeholder="Rp 0" oninput="formatRupiahInput(this); calculateRekap();" class="rekap-input" style="font-size: 18px; color: #0f172a; font-weight: 800;" {{ auth()->user()->name === 'owner_pusat' ? 'disabled' : '' }} />
                            </div>
                            <div class="input-group" style="margin-bottom: 15px;">
                                <label>Pembayaran Non-Tunai (QRIS/Transfer):</label>
                                <input type="text" inputmode="numeric" id="input-uang-qris" placeholder="Rp 0" oninput="formatRupiahInput(this); calculateRekap();" class="rekap-input" style="font-size: 18px; color: #0f172a; font-weight: 800;" {{ auth()->user()->name === 'owner_pusat' ? 'disabled' : '' }} />
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: var(--text-muted); font-weight: 600; padding-top: 10px; border-top: 1px solid #a7f3d0;">
                                <span>Total Uang (Fisik + QRIS + Keluar):</span>
                                <span style="color: #0284c7;">Rp <span id="rekap-uang-asli">0</span></span>
                            </div>
                            
                            <div id="rekap-selisih-box" style="margin: 15px 0; padding: 15px; background: white; border-radius: 8px; text-align: center; font-size: 14px; font-weight: bold; border: 1px solid #cbd5e1;">
                                <span style="color:#64748b;">Ketik uang fisik di laci untuk melihat selisih...</span>
                            </div>
                        </div>

                        <div style="text-align: right; margin-top: 15px;">
                            <span style="display: block; font-size: 13px; color: var(--primary); font-weight: bold;">Tabungan Wajib Disetor:</span>
                            <span style="font-size: 28px; font-weight: 900; color: var(--primary);">Rp <span id="rekap-tabungan">0</span></span>
                        </div>
                    </div>
                </div>
                
                <!-- HANYA KASIR YANG BOLEH KLIK TUTUP BUKU -->
                @if(auth()->user()->name !== 'owner_pusat')
                <button type="button" class="btn-modern btn-success" onclick="simpanLaporanHarian(this)" style="width: 100%; justify-content: center; padding: 18px; font-size: 16px; font-weight: 800;">
                    💾 SIMPAN ANALISIS & TUTUP BUKU SHIFT
                </button>
                @endif
            </div>
        </div> <!-- END TAB LAPORAN -->
        
        <!-- MEMANGGIL MODALS -->
        @include('partials.modals')

    </div>
</div>
@endif
@endsection

@section('scripts')
    <!-- 1. Panggil library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 2. Data Chart dari MySQL ke JavaScript -->
    <script>
        var chartDataMySQL = {
            jam: @json($dataGrafikJam ?? []),
            harian: @json($dataGrafikHarian ?? []),
            labelHarian: @json($labelGrafikHarian ?? []),
            labelCabang: @json($labelCabang ?? []),       
            omzetCabang: @json($dataOmzetCabang ?? [])    
        };
    </script>

    <!-- 3. Script Utama Kasir -->
    <script src="{{ asset('js/script.js') }}"></script>
@endsection