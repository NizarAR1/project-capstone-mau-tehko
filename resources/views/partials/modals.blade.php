
<!-- MODAL LAPORAN STOK PAGI (KHUSUS LEADER) -->
<div id="modal-stok-pagi" class="modal" style="z-index: 999999;">
  <div class="modal-content" style="max-width: 400px; text-align: left; padding: 25px; max-height: 85vh; display: flex; flex-direction: column;">
    <h3 id="judul-stok-pagi" style="color: #0284c7; margin-bottom: 5px; text-align: center;">☀️ Laporan Stok Pagi</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px; text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 10px;">
      Data fisik riil yang dihitung kasir saat membuka shift hari ini.
    </p>
    
    <!-- Wadah Rincian Stok Pagi -->
    <div id="isi-stok-pagi" class="custom-scrollbar" style="overflow-y: auto; flex: 1; padding-right: 8px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px;">
      <!-- Akan diisi otomatis oleh JavaScript -->
    </div>
    
    <button onclick="document.getElementById('modal-stok-pagi').classList.remove('active')" style="width: 100%; padding: 12px; background: #64748b; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 14px; cursor: pointer;">
      Tutup Laporan
    </button>
  </div>
</div>

<!-- MODAL OPENING SHIFT (STOK AWAL) -->
<div id="opening-stock-modal" class="modal" style="z-index: 999999;">
  <div class="modal-content" style="max-width: 400px; text-align: left; padding: 25px; max-height: 85vh; display: flex; flex-direction: column;">
    <h3 style="color: #2e7d32; margin-bottom: 5px; text-align: center;">☀️ Buka Shift (Opening)</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px; text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 10px;">
      Validasi Stok Pagi! Hitung dan pastikan fisik bahan baku sesuai dengan sistem sebelum berjualan.
    </p>
    
    <!-- Wadah List Bahan Baku -->
    <div id="opening-stock-list" class="custom-scrollbar" style="overflow-y: auto; flex: 1; padding-right: 8px; display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px;">
      <!-- Akan diisi otomatis oleh JavaScript -->
    </div>
    
    <button onclick="simpanOpeningStock()" style="width: 100%; padding: 15px; background: #27ae60; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 4px 6px rgba(39, 174, 96, 0.3);">
      Simpan & Mulai Berjualan 🚀
    </button>
  </div>
</div>

<!-- MODAL TARIK SALDO -->
<div id="tarik-saldo-modal" class="modal">
  <div class="modal-content" style="max-width: 350px;">
    <h3 id="tarik-saldo-title" style="margin-bottom: 10px">Tarik Saldo</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Catat pengeluaran agar riwayat saldo transparan:</p>
    
    <input type="hidden" id="input-tarik-jenis" />
    
    <div style="margin-bottom: 15px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Keperluan / Keterangan:</label>
      <input type="text" id="input-tarik-ket" placeholder="....." style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #ccc; border-radius: 8px; outline: none; margin-top: 5px;" />
    </div>

    <div style="margin-bottom: 20px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Nominal Digunakan (Rp):</label>
      <input type="text" inputmode="numeric" id="input-tarik-nominal" placeholder="0" oninput="formatRupiahInput(this)" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #ccc; border-radius: 8px; outline: none; margin-top: 5px; font-weight: bold;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <button class="btn-potong-saldo" onclick="prosesTarikSaldo()" style="width: 100%; padding: 12px; font-size: 15px;">Potong Saldo</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="closeModalTarikSaldo()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI KELUAR -->
<div id="logout-modal" class="modal">
  <div class="modal-content">
    <h3 style="color: #c0392b">Konfirmasi Keluar</h3>
    <p>Yakin ingin keluar?</p>
    <div class="modal-actions">
      <form method="POST" action="/logout" style="margin:0; padding:0; width:100%;">
          @csrf
          <button type="submit" class="btn-yes" style="background-color: #c0392b; width:100%;">Ya</button>
      </form>
      <button class="btn-no" onclick="closeLogoutModal()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL PEMBAYARAN KASIR -->
<div id="payment-modal" class="modal">
  <div class="modal-content">
    <h3 style="color: #2e7d32; margin-bottom: 5px">Proses Pembayaran</h3>
    <p style="font-size: 18px; margin-top: 5px; color: #555">Total Tagihan: <strong style="color: #c0392b; font-size: 22px">Rp <span id="payment-total">0</span></strong></p>
    
    <div style="margin: 20px 0">
      <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Uang Diterima (Rp):</label>
      <input type="text" inputmode="numeric" id="payment-input" oninput="formatRupiahInput(this); calculateChange();" placeholder="Ketik jumlah uang..." style="width: 100%; padding: 15px; font-size: 20px; text-align: center; border: 2px solid #2e7d32; border-radius: 8px; outline: none; font-weight: bold;" />
      
      <div style="display: flex; gap: 8px; margin-top: 12px; overflow-x: auto; padding-bottom: 5px;">
        <button type="button" onclick="setQuickCash('pas')" style="flex: 1; padding: 10px; background: #27ae60; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; white-space: nowrap;">Uang Pas</button>
        <button type="button" onclick="setQuickCash(10000)" style="flex: 1; padding: 10px; background: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px; font-weight: bold; cursor: pointer; white-space: nowrap; color: #333;">10k</button>
        <button type="button" onclick="setQuickCash(20000)" style="flex: 1; padding: 10px; background: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px; font-weight: bold; cursor: pointer; white-space: nowrap; color: #333;">20k</button>
        <button type="button" onclick="setQuickCash(50000)" style="flex: 1; padding: 10px; background: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px; font-weight: bold; cursor: pointer; white-space: nowrap; color: #333;">50k</button>
        <button type="button" onclick="setQuickCash(100000)" style="flex: 1; padding: 10px; background: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px; font-weight: bold; cursor: pointer; white-space: nowrap; color: #333;">100k</button>
      </div>
    </div>
    
    <p style="font-size: 18px; margin-bottom: 20px; color: #555">Kembalian: <strong style="color: #27ae60; font-size: 22px">Rp <span id="payment-change">0</span></strong></p>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <button class="btn-yes" id="btn-confirm-payment" style="background-color: #2e7d32; width: 100%; padding: 15px; font-size: 16px;" onclick="finalizeCheckout(false)">✅ Simpan Transaksi Biasa</button>
      <button class="btn-yes" id="btn-print-payment" style="background-color: #2980b9; width: 100%; padding: 15px; font-size: 16px; border: none; border-radius: 8px; color: white; cursor: pointer; font-weight: bold;" onclick="finalizeCheckout(true)">🖨️ Simpan & Cetak Nota</button>
      <button class="btn-no" style="width: 100%; padding: 15px; font-size: 16px; background-color: #7f8c8d;" onclick="closePaymentModal()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL INPUT NAMA ANTREAN -->
<div id="queue-name-modal" class="modal">
  <div class="modal-content" style="max-width: 350px;">
    <h3 style="color: #e65100; margin-bottom: 10px">Simpan Antrean</h3>
    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">Masukkan nama pembeli atau nomor meja:</p>
    
    <div style="margin-bottom: 20px;">
      <input type="text" id="input-queue-name" placeholder="Cth: Budi / Meja 3" style="width: 100%; padding: 12px; font-size: 16px; text-align: center; border: 2px solid #f39c12; border-radius: 8px; outline: none; font-weight: bold;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px;">
      <button class="btn-yes" style="background-color: #f39c12; width: 100%; padding: 12px; font-size: 15px;" onclick="konfirmasiSimpanAntrean()">Simpan ke Antrean</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="closeQueueModal()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL CUSTOM MENU (INPUT MANUAL) -->
<div id="custom-menu-modal" class="modal">
  <div class="modal-content" style="max-width: 400px;">
    <h3 style="color: #e65100; margin-bottom: 10px">Input Menu Custom</h3>
    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">Masukkan nama menu/pesanan dan harganya:</p>
    
    <div style="margin-bottom: 15px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Nama Pesanan:</label>
      <input type="text" id="input-custom-name" placeholder="Cth: Lemon Tea Tumbler" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #f39c12; border-radius: 8px; outline: none; margin-top: 5px;" />
    </div>

    <div style="margin-bottom: 20px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Harga (Rp):</label>
      <input type="text" inputmode="numeric" id="input-custom-price" placeholder="0" oninput="formatRupiahInput(this)" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #f39c12; border-radius: 8px; outline: none; margin-top: 5px;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <button class="btn-yes" style="background-color: #f39c12; width: 100%; padding: 12px; font-size: 15px;" onclick="tambahCustomMenu()">Tambah ke Keranjang</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="closeCustomMenuModal()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL EDIT SALDO AWAL (KHUSUS LEADER) -->
<div id="edit-saldo-modal" class="modal">
  <div class="modal-content" style="max-width: 350px; position: relative; z-index: 999999; pointer-events: auto;">
    <h3 id="edit-saldo-title" style="margin-bottom: 10px; color: #333;">Edit Saldo Real</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Sesuaikan angka di sistem agar sama dengan uang fisik di lapangan saat ini:</p>
    
    <input type="hidden" id="input-edit-jenis" />
    <input type="hidden" id="input-edit-cabang" />

    <div style="margin-bottom: 20px; text-align: left; position: relative; z-index: 999999; pointer-events: auto;">
      <label style="font-size: 13px; font-weight: bold; color: #333; display: block; pointer-events: none;">Masukkan Total Saldo Asli (Rp):</label>
      <input type="text" inputmode="numeric" id="input-edit-nominal" placeholder="0" oninput="formatRupiahInput(this)" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #2e7d32; border-radius: 8px; outline: none; margin-top: 5px; font-weight: bold; background: white; color: black; z-index: 100000; position: relative; pointer-events: auto;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px; pointer-events: auto;">
      <button class="btn-yes" id="btn-konfirm-edit" style="width: 100%; padding: 12px; font-size: 15px; background-color: #27ae60; pointer-events: auto;" onclick="simpanEditSaldo()">Simpan Saldo Baru</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d; pointer-events: auto;" onclick="closeModalEditSaldo()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL SETOR SALDO (REALTIME) -->
<div id="setor-saldo-modal" class="modal">
  <div class="modal-content" style="max-width: 350px;">
    <h3 id="setor-saldo-title" style="margin-bottom: 10px; color: #27ae60;">Setor Tabungan Wajib</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Masukkan Jumlah Tabungan Wajib Sementara:</p>
    
    <input type="hidden" id="input-setor-jenis" />
    
    <div style="margin-bottom: 20px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Nominal Disetor (Rp):</label>
      <input type="text" inputmode="numeric" id="input-setor-nominal" placeholder="0" oninput="formatRupiahInput(this)" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #27ae60; border-radius: 8px; outline: none; margin-top: 5px; font-weight: bold;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <button class="btn-yes" style="width: 100%; padding: 12px; font-size: 15px; background-color: #27ae60;" onclick="prosesSetorSaldo()">Tambahkan ke Saldo</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="closeModalSetorSaldo()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL ABSENSI KARYAWAN -->
<div id="absensi-modal" class="modal">
  <div class="modal-content" style="max-width: 350px;">
    <h3 style="color: #2980b9; margin-bottom: 10px">Absensi Karyawan</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Ketik nama dan pilih shift Anda untuk melakukan absen.</p>
    
    <div style="margin-bottom: 15px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Nama Panggilan:</label>
      <input type="text" id="input-absen-nama" list="list-nama-karyawan" autocomplete="off" placeholder="Pilih atau Ketik Nama..." style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #2980b9; border-radius: 8px; outline: none; margin-top: 5px; text-align: center; font-weight: bold;" />
      <datalist id="list-nama-karyawan"></datalist>
    </div>

    <div style="margin-bottom: 20px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Pilih Jadwal Shift:</label>
      <select id="input-absen-shift" style="width: 100%; padding: 12px; font-size: 14px; border: 2px solid #2980b9; border-radius: 8px; outline: none; margin-top: 5px; cursor: pointer; font-weight: bold;">
        <option value="Shift Pagi (08.20 - 16.20)">☀️ Shift Pagi (08.20 - 16.20)</option>
        <option value="Shift Sore (15.00 - 22.00)">🌙 Shift Sore (15.00 - 22.00)</option>
        <option value="Full (08.20 - 22.00)">🔥 Full (08.20 - 22.00)</option>
      </select>
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <div style="display: flex; gap: 10px;">
        <button class="btn-yes" id="btn-absen-masuk" style="flex: 1; background-color: #27ae60; padding: 12px; font-size: 14px;" onclick="prosesAbsen('masuk')">Hadir 🟢</button>
        <button class="btn-yes" id="btn-absen-pulang" style="flex: 1; background-color: #c0392b; padding: 12px; font-size: 14px;" onclick="prosesAbsenPulangInstan()">Pulang 🔴</button>
      </div>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 14px; background-color: #7f8c8d;" onclick="tutupModalAbsensi()">Tutup</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI ABSEN BERHASIL -->
<div id="absen-success-modal" class="modal" style="z-index: 10000;">
  <div class="modal-content" style="max-width: 320px; text-align: center; padding: 35px 20px;">
    <div style="font-size: 60px; margin-bottom: 10px; animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">✅</div>
    <h3 style="color: #27ae60; margin-bottom: 10px; font-weight: 800;">Absen Berhasil!</h3>
    <p id="absen-success-msg" style="font-size: 14px; color: #555; margin-bottom: 25px; line-height: 1.6;"></p>
    
    <button onclick="tutupModalSuksesAbsen()" style="background-color: #27ae60; color: white; border: none; width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);">
      Oke, Siap Kerja!
    </button>
  </div>
</div>

<!-- MODAL BARANG MASUK (DROP STOK) -->
<div id="modal-barang-masuk" class="modal">
  <div class="modal-content" style="max-width: 350px;">
    <h3 style="color: #2980b9; margin-bottom: 10px">🚚 Catat Drop Stok</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Catat barang yang baru dikirim oleh tim lapangan / supplier hari ini.</p>
    
    <div style="margin-bottom: 15px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Pilih Bahan Baku:</label>
      <select id="input-bm-id" style="width: 100%; padding: 12px; font-size: 14px; border: 2px solid #2980b9; border-radius: 8px; outline: none; margin-top: 5px; font-weight: bold;">
        <!-- Akan diisi otomatis -->
      </select>
    </div>

    <div style="margin-bottom: 20px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Jumlah Ditambahkan (+):</label>
      <input type="number" inputmode="numeric" id="input-bm-qty" placeholder="Cth: 200" style="width: 100%; padding: 12px; font-size: 15px; border: 2px solid #2980b9; border-radius: 8px; outline: none; margin-top: 5px; font-weight: bold; text-align: center;" />
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px">
      <button class="btn-yes" style="width: 100%; padding: 12px; font-size: 15px; background-color: #2980b9;" onclick="simpanBarangMasuk()">Tambahkan Stok</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="tutupModalBarangMasuk()">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL MANAJEMEN KARYAWAN -->
<div id="modal-manajemen-karyawan" class="modal" style="z-index: 99999;">
  <div class="modal-content" style="max-width: 450px;">
    <h3 style="color: #c0392b; margin-bottom: 10px;">👥 Manajemen Data Karyawan</h3>
    <p style="font-size: 13px; color: #555; margin-bottom: 15px;">Pilih karyawan untuk mengedit nama, menyesuaikan total tabungan, atau menghapus datanya.</p>
    
    <div style="margin-bottom: 15px; text-align: left;">
      <label style="font-size: 13px; font-weight: bold; color: #333;">Pilih Karyawan:</label>
      <select id="select-karyawan-hapus" onchange="cekTabunganKaryawan()" style="width: 100%; padding: 12px; font-size: 14px; border: 2px solid #c0392b; border-radius: 8px; outline: none; margin-top: 5px; cursor: pointer; font-weight: bold;">
        <option value="">-- Pilih Karyawan --</option>
      </select>
    </div>

    <div id="form-edit-karyawan" style="display: none; background: #fdfaf6; border: 1px dashed #d35400; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left;">
        <div style="margin-bottom: 15px;">
            <label style="font-size: 12px; font-weight: bold; color: #7f8c8d;">Edit Nama Karyawan:</label>
            <input type="text" id="edit-nama-karyawan" placeholder="Ketik nama baru..." style="width: 100%; padding: 10px; font-size: 15px; border: 1px solid #ccc; border-radius: 6px; outline: none; margin-top: 5px; font-weight: bold; color: #333;" />
        </div>
        <div>
            <label style="font-size: 12px; font-weight: bold; color: #7f8c8d;">Edit Total Tabungan Jaminan (Rp):</label>
            <input type="text" inputmode="numeric" id="edit-tabungan-karyawan" oninput="formatRupiahInput(this)" placeholder="0" style="width: 100%; padding: 10px; font-size: 20px; border: 1px solid #d35400; border-radius: 6px; outline: none; margin-top: 5px; font-weight: 900; color: #d35400;" />
        </div>
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px;">
      <button class="btn-yes" id="btn-simpan-karyawan" style="width: 100%; padding: 12px; font-size: 15px; background-color: #27ae60;" onclick="simpanEditKaryawan()" disabled>💾 Simpan Perubahan</button>
      <button class="btn-yes" id="btn-hapus-karyawan" style="width: 100%; padding: 12px; font-size: 15px; background-color: #c0392b;" onclick="prosesHapusKaryawan()" disabled>🗑️ Hapus Data Karyawan</button>
      <button class="btn-no" style="width: 100%; padding: 12px; font-size: 15px; background-color: #7f8c8d;" onclick="tutupModalManajemenKaryawan()">Batal / Tutup</button>
    </div>
  </div>
</div>

<!-- MODAL DETAIL KARYAWAN CUSTOM -->
<div id="modal-detail-karyawan" class="modal" style="z-index: 99999;">
  <div class="modal-content" style="max-width: 380px; text-align: center; padding: 25px;">
    <div style="font-size: 45px; margin-bottom: 8px;">👤</div>
    <h3 id="detail-nama-karyawan" style="color: #0284c7; margin-bottom: 5px; font-weight: 800; text-transform: uppercase;"></h3>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Rincian Tabungan & Status Karyawan</p>
    
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; margin-bottom: 20px; text-align: left;">
      <div style="display: flex; justify-content: space-between; font-size: 14px; color: #334155; margin-bottom: 8px;">
        <span>Total Tabungan Kontrak:</span>
        <strong id="detail-nominal-tabungan" style="color: #16a34a; font-size: 16px;">Rp 0</strong>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748b;">
        <span>Status Sistem:</span>
        <span style="color: #0284c7; font-weight: 600;">Aman & Sinkron</span>
      </div>
    </div>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px;">
      <button onclick="tutupModalDetailKaryawan()" style="background-color: #0284c7; color: white; border: none; width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; box-shadow: 0 4px 6px rgba(2, 132, 199, 0.2);">
        Tutup
      </button>
    </div>
  </div>
</div>