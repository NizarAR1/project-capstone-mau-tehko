<div class="sidebar" style="width: 260px; background: #0f172a; color: white; padding: 25px 20px; display: flex; flex-direction: column; box-shadow: 2px 0 15px rgba(0,0,0,0.05); height: 100vh; position: sticky; top: 0;">
    
    <!-- Logo / Judul Aplikasi -->
    <div style="text-align: center; margin-bottom: 35px; padding-bottom: 20px; border-bottom: 1px solid #1e293b;">
        <h2 style="color: #10b981; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px;">Mau Teh-ko</h2>
        <p style="color: #94a3b8; font-size: 13px; margin: 5px 0 0 0; font-weight: 500;">Modern POS System</p>
    </div>
    
    <!-- Daftar Menu -->
    <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
        
        <!-- HANYA KASIR YANG BISA LIHAT KASIR -->
        @if(auth()->check() && auth()->user()->name !== 'owner_pusat')
        <li>
            <a onclick="switchTab('pos')" style="color: #cbd5e1; text-decoration: none; font-size: 15px; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-weight: 500;" onmouseover="this.style.background='#1e293b'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#cbd5e1'">
                <span style="font-size: 18px;">🛒</span> Kasir
            </a>
        </li>
        @endif

        <!-- SEMUA BISA LIHAT STOK & LAPORAN -->
        <li>
            <a onclick="switchTab('stok')" style="color: #cbd5e1; text-decoration: none; font-size: 15px; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-weight: 500;" onmouseover="this.style.background='#1e293b'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#cbd5e1'">
                <span style="font-size: 18px;">📦</span> Stok Bahan
            </a>
        </li>
        <li>
            <a onclick="switchTab('laporan')" style="color: #cbd5e1; text-decoration: none; font-size: 15px; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-weight: 500;" onmouseover="this.style.background='#1e293b'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#cbd5e1'">
                <span style="font-size: 18px;">📊</span> Laporan
            </a>
        </li>

        <!-- HANYA OWNER YANG BISA LIHAT DASHBOARD PUSAT -->
        @if(auth()->check() && auth()->user()->name === 'owner_pusat')
        <li style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #1e293b;">
            <a onclick="switchTab('dashboard-pusat')" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; font-size: 15px; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s; font-weight: 600; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(5, 150, 105, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 10px rgba(5, 150, 105, 0.2)'">
                <span style="font-size: 18px;">👑</span> Dashboard Pusat
            </a>
        </li>
        
        <!-- Script pintar untuk menjaga tab aktif saat ganti cabang -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('tab') === 'laporan') {
                    switchTab('laporan'); 
                } else if (urlParams.get('tab') === 'stok') {
                    switchTab('stok'); 
                } else {
                    switchTab('dashboard-pusat'); 
                }
            });
        </script>
        @endif
    </ul>
</div>