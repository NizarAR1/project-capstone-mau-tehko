<div class="modern-topbar" style="background: white; padding: 16px 25px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; width: 100%;">
    
    <!-- Bagian Kiri: Info User -->
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="width: 45px; height: 45px; background: #ecfdf5; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 22px; color: #059669;">
            @if(auth()->user()->name === 'owner_pusat') 👑 @else 🧑‍🍳 @endif
        </div>
        <div>
            <h3 style="margin: 0; color: #0f172a; font-size: 18px; font-weight: 800;">
                Halo, {{ auth()->user()->name ?? 'Karyawan' }}
            </h3>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 13px; font-weight: 500;">
                @if(auth()->user()->name === 'owner_pusat')
                    <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: bold;">Owner / Administrator</span>
                @else
                    Status: <strong style="color: #059669;">Kasir Aktif</strong>
                @endif
            </p>
        </div>
    </div>
    
    <!-- Bagian Kanan: Jam Realtime & Tombol Logout -->
    <div style="display: flex; align-items: center; gap: 20px;">
        
        <!-- Jam Digital (Hanya tampil di layar agak lebar) -->
        <div class="topbar-clock" style="text-align: right; border-right: 2px solid #e2e8f0; padding-right: 20px;">
            <div id="live-clock" style="color: #0f172a; font-weight: 800; font-size: 16px; letter-spacing: 0.5px;">00:00:00</div>
            <div id="live-date" style="color: #64748b; font-size: 12px; font-weight: 500; margin-top: 2px;">Memuat tanggal...</div>
        </div>
        
        <!-- Tombol Keluar Modern -->
        <form method="POST" action="/logout" style="margin: 0;">
            @csrf
            <button type="submit" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; padding: 10px 18px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='white'" onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444'">
                <span>Keluar</span>
                <span style="font-size: 16px;">🚪</span>
            </button>
        </form>
    </div>
</div>

<!-- Script untuk Jam Digital Topbar -->
<script>
    function updateClockTopbar() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' });
        
        const clockEl = document.getElementById('live-clock');
        const dateEl = document.getElementById('live-date');
        
        if(clockEl) clockEl.innerText = timeStr;
        if(dateEl) dateEl.innerText = dateStr;
    }
    setInterval(updateClockTopbar, 1000);
    updateClockTopbar();
</script>

<style>
    /* Menyembunyikan jam di HP agar layout tidak berantakan */
    @media (max-width: 768px) {
        .topbar-clock { display: none; }
    }
</style>