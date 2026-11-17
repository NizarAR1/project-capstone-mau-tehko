// ==========================================
// 1. STATE & VARIABEL GLOBAL (MYSQL MODE)
// ==========================================
let localCart = [];
let currentCheckoutTotal = 0;

// ==========================================
// 2. FITUR JAM REAL-TIME & UI DASAR
// ==========================================
function updateLiveClock() {
    const now = new Date();
    const optionsDate = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
    const dateString = now.toLocaleDateString("id-ID", optionsDate);
    const timeString = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", second: "2-digit" });

    const clockEl = document.getElementById("live-clock");
    if (clockEl) clockEl.innerText = timeString;

    const dateEl = document.getElementById("live-date");
    if (dateEl) dateEl.innerText = dateString;
}
setInterval(updateLiveClock, 1000);

function formatRupiahInput(element) {
    let value = element.value.replace(/[^0-9]/g, "");
    if (value) {
        element.value = parseInt(value, 10).toLocaleString("id-ID");
    } else {
        element.value = "";
    }
}

function togglePassword() {
    const passInput = document.getElementById("password");
    const toggleBtn = document.getElementById("toggle-password");
    if (passInput.type === "password") {
        passInput.type = "text";
        toggleBtn.innerHTML = '🐵 Lihat Password';
    } else {
        passInput.type = "password";
        toggleBtn.innerHTML = '🙈 Sembunyikan';
    }
}

// ==========================================
// 3. NAVIGASI TAB & FILTER MENU
// ==========================================
function switchTab(tabId) {
    document.querySelectorAll(".tab-content").forEach((tab) => {
        tab.classList.remove("active");
        tab.style.display = 'none';
    });
    const targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.add("active");
        targetTab.style.display = 'block';
    }
}

function switchSubMenuPusat(targetId, btnElement) {
    document.querySelectorAll('.sub-content-pusat').forEach(el => el.style.display = 'none');
    const targetEl = document.getElementById(targetId);
    if (targetEl) targetEl.style.display = 'block';
    
    document.querySelectorAll('.sub-btn').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');
}

function filterCategory(categoryName, btn) {
    document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    
    document.getElementById("search-input").value = "";
    
    let catId = 'semua';
    let keyword = categoryName.toLowerCase();
    
    if (keyword === 'teh') catId = '1';
    else if (keyword === 'susu') catId = '2';
    else if (keyword === 'kopi') catId = '3';
    else if (keyword === 'syrup') catId = '4';
    else if (keyword === 'lainnya') catId = '5';

    document.querySelectorAll('.menu-item').forEach(item => {
        let itemCat = item.getAttribute('data-kategori');
        if (catId === 'semua' || itemCat === catId) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function searchMenu() {
    let keyword = document.getElementById("search-input").value.toLowerCase();
    document.querySelectorAll('.menu-item').forEach(item => {
        let text = item.querySelector('h3').innerText.toLowerCase();
        if(text.includes(keyword)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// ==========================================
// 4. KERANJANG KASIR (CART)
// ==========================================
function tambahKeKeranjang(id, name, price) {
    let existing = localCart.find(item => item.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        localCart.push({ id: id, name: name, price: parseInt(price), qty: 1 });
    }
    renderCart();
}

function incrementCartItem(id) {
    let existing = localCart.find(item => item.id === id);
    if (existing) existing.qty += 1;
    renderCart();
}

function removeFromCart(id) {
    let index = localCart.findIndex(item => item.id === id);
    if (index > -1) {
        localCart[index].qty -= 1;
        if (localCart[index].qty <= 0) localCart.splice(index, 1);
    }
    renderCart();
}

function renderCart() {
    const cartItems = document.getElementById("cart-items");
    const totalPriceEl = document.getElementById("total-price");
    cartItems.innerHTML = "";
    let total = 0;

    if (localCart.length === 0) {
        cartItems.innerHTML = '<li style="justify-content:center; color:#999; font-weight:500; font-style:italic; border-bottom: none; margin-top:15px;">Keranjang kosong...</li>';
    } else {
        localCart.forEach(item => {
            total += item.price * item.qty;
            cartItems.innerHTML += `
            <li>
                <div style="flex:1;">
                    <strong style="display:block; font-size:14px;">${item.name}</strong>
                    <small style="color:var(--primary); font-weight:bold;">Rp ${item.price.toLocaleString("id-ID")}</small>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <button onclick="removeFromCart('${item.id}')" style="padding:4px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-app); cursor:pointer; font-weight:bold; color: var(--text-main);">-</button>
                    <span style="font-weight:bold; width:20px; text-align:center;">${item.qty}</span>
                    <button onclick="incrementCartItem('${item.id}')" style="padding:4px 10px; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-app); cursor:pointer; font-weight:bold; color: var(--text-main);">+</button>
                </div>
            </li>`;
        });
    }
    currentCheckoutTotal = total;
    totalPriceEl.innerText = total.toLocaleString("id-ID");
}

// ==========================================
// 5. CHECKOUT & TRANSAKSI
// ==========================================
function checkout() {
    if (localCart.length === 0) return alert("Keranjang kosong! Silakan pilih menu dulu.");
    
    const totalElement = document.getElementById("payment-total");
    const inputElement = document.getElementById("payment-input");
    const changeElement = document.getElementById("payment-change");

    if (totalElement) totalElement.innerText = currentCheckoutTotal.toLocaleString("id-ID");
    if (inputElement) inputElement.value = "";
    if (changeElement) {
        changeElement.innerText = "0";
        changeElement.style.color = "var(--primary)";
    }

    const modal = document.getElementById("payment-modal");
    if (modal) modal.classList.add("active");
}

function closePaymentModal() {
    const modal = document.getElementById("payment-modal");
    if (modal) modal.classList.remove("active");
}

function setQuickCash(amount) {
    const input = document.getElementById("payment-input");
    if (!input) return;
    if (amount === "pas") input.value = currentCheckoutTotal.toLocaleString("id-ID");
    else input.value = amount.toLocaleString("id-ID");
    calculateChange();
}

function calculateChange() {
    const inputElement = document.getElementById("payment-input");
    if (!inputElement) return;

    const uangMasuk = parseInt(inputElement.value.replace(/\./g, "")) || 0;
    const kembalian = uangMasuk - currentCheckoutTotal;
    const changeEl = document.getElementById("payment-change");

    if (kembalian < 0 && uangMasuk > 0) {
        changeEl.innerText = "Uang Kurang!";
        changeEl.style.color = "#ef4444";
    } else {
        changeEl.innerText = kembalian > 0 ? kembalian.toLocaleString("id-ID") : "0";
        changeEl.style.color = "var(--primary)";
    }
}

function finalizeCheckout(cetakNota = false) {
    const uangMasuk = parseInt(document.getElementById("payment-input").value.replace(/\./g, "")) || 0;
    if (uangMasuk < currentCheckoutTotal && currentCheckoutTotal > 0) {
        return alert("Uang pembeli kurang dari total tagihan!");
    }

    let btnConfirm = document.getElementById("btn-confirm-payment");
    let txtAsli = btnConfirm.innerText;
    btnConfirm.innerText = "Menyimpan...";
    btnConfirm.disabled = true;

    // Kirim data transaksi ke API Laravel
    fetch('/simpan-transaksi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            cart: localCart,
            total: currentCheckoutTotal,
            bayar: uangMasuk
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (cetakNota) window.print();
            alert("✅ Transaksi berhasil disimpan!");
            localCart = [];
            renderCart();
            closePaymentModal();
            location.reload(); 
        } else {
            alert("❌ Gagal: " + data.message);
        }
    })
    .catch(error => alert("Terjadi kesalahan jaringan."))
    .finally(() => {
        btnConfirm.innerText = txtAsli;
        btnConfirm.disabled = false;
    });
}

// ==========================================
// 6. STOK MANUAL (LARAVEL)
// ==========================================
function simpanStokLaravel(btnElement) {
    let inputs = document.querySelectorAll('.input-stok-bahan');
    let stokData = [];

    inputs.forEach(input => {
        stokData.push({
            id: input.getAttribute('data-id'),
            jumlah: input.value
        });
    });

    if (stokData.length === 0) return alert('Tidak ada data stok untuk disimpan.');

    let tulisanAwal = btnElement.innerHTML;
    btnElement.innerHTML = "⏳ MENYIMPAN KE DATABASE...";
    btnElement.disabled = true;

    fetch('/update-stok', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ stok_data: stokData })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload(); 
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => alert('Terjadi kesalahan saat menyimpan stok.'))
    .finally(() => {
        btnElement.innerHTML = tulisanAwal;
        btnElement.disabled = false;
    });
}

// ==========================================
// 7. TUTUP BUKU / REKAPITULASI (LARAVEL)
// ==========================================
function addPengeluaranRow() {
    const list = document.getElementById("pengeluaran-list");
    const id = "peng_" + Date.now();
    const div = document.createElement("div");
    div.id = id;
    div.style.display = "flex";
    div.style.gap = "10px";
    div.style.marginBottom = "10px";
    
    div.innerHTML = `
        <input type="text" class="input-nama-pengeluaran rekap-input" placeholder="Nama (Cth: Es Kristal)" style="flex: 2; font-size: 13px; padding: 10px;" oninput="calculateRekap()">
        <input type="text" inputmode="numeric" class="input-nominal-pengeluaran rekap-input" placeholder="Rp 0" style="flex: 1.5; font-size: 13px; padding: 10px;" oninput="formatRupiahInput(this); calculateRekap()">
        <button type="button" onclick="document.getElementById('${id}').remove(); calculateRekap()" style="background: #ef4444; color: white; border: none; border-radius: 6px; padding: 0 12px; cursor: pointer; font-weight: bold;">X</button>
    `;
    list.appendChild(div);
}

function calculateRekap() {
    let pengeluaran = 0;
    document.querySelectorAll(".input-nominal-pengeluaran").forEach((input) => {
        pengeluaran += parseInt(input.value.replace(/\./g, "")) || 0;
    });
    
    const elTotalPeng = document.getElementById("rekap-total-pengeluaran");
    if (elTotalPeng) elTotalPeng.innerText = pengeluaran.toLocaleString("id-ID");

    let laci = parseInt(document.getElementById('input-uang-laci')?.value.replace(/\./g, '')) || 0;
    let qris = parseInt(document.getElementById('input-uang-qris')?.value.replace(/\./g, '')) || 0;
    let pendapatan = parseInt(document.getElementById('rekap-total-penjualan')?.innerText.replace(/\./g, '')) || 0;

    let uangAsli = pengeluaran + laci + qris;
    let elUangAsli = document.getElementById('rekap-uang-asli');
    if(elUangAsli) elUangAsli.innerText = uangAsli.toLocaleString('id-ID');

    let selisih = uangAsli - pendapatan;
    let selisihBox = document.getElementById('rekap-selisih-box');
    if(selisihBox) {
        if(selisih < 0) selisihBox.innerHTML = `<span style="color:#ef4444;">🚨 Selisih (Kurang): -Rp ${Math.abs(selisih).toLocaleString('id-ID')}</span>`;
        else if(selisih > 0) selisihBox.innerHTML = `<span style="color:#f59e0b;">💡 Selisih (Lebih): +Rp ${selisih.toLocaleString('id-ID')}</span>`;
        else selisihBox.innerHTML = `<span style="color:#10b981;">✅ Selisih Pas (Rp 0)</span>`;
    }

    let tabungan = uangAsli - pengeluaran - qris;
    let elTabungan = document.getElementById('rekap-tabungan');
    if(elTabungan) elTabungan.innerText = (tabungan < 0 ? 0 : tabungan).toLocaleString('id-ID');
}

function simpanLaporanHarian(btn) {
    let laci = parseInt(document.getElementById('input-uang-laci').value.replace(/\./g, '')) || 0;
    let qris = parseInt(document.getElementById('input-uang-qris').value.replace(/\./g, '')) || 0;
    
    let pengeluaranData = [];
    document.querySelectorAll("#pengeluaran-list > div").forEach(row => {
        let nama = row.querySelector(".input-nama-pengeluaran").value.trim();
        let nominal = parseInt(row.querySelector(".input-nominal-pengeluaran").value.replace(/\./g, '')) || 0;
        if(nama && nominal > 0) {
            pengeluaranData.push({ nama: nama, nominal: nominal });
        }
    });

    if (!confirm("Anda yakin ingin menyimpan laporan Tutup Buku shift hari ini?")) return;

    let txt = btn.innerText;
    btn.innerText = "⏳ MENYIMPAN ARSIP...";
    btn.disabled = true;

    fetch('/simpan-laporan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ 
            uang_laci: laci, 
            uang_qris: qris,
            pengeluaran: pengeluaranData 
        })
    })
    .then(res => res.json())
    .then(data => {
        alert("✅ " + data.message);
        if (data.success) location.reload();
    })
    .catch(err => alert("Gagal koneksi server."))
    .finally(() => {
        btn.innerText = txt;
        btn.disabled = false;
    });
}

// ==========================================
// 8. DASHBOARD PUSAT OWNER (CHART & EXCEL)
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    muatGrafikLeaderboard();
});

function muatGrafikLeaderboard() {
    const ctx = document.getElementById('leaderboardChart');
    if (!ctx || typeof chartDataMySQL === 'undefined' || !chartDataMySQL.labelCabang) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartDataMySQL.labelCabang,
            datasets: [{
                label: 'Omzet Hari Ini (Rp)',
                data: chartDataMySQL.omzetCabang,
                backgroundColor: 'rgba(5, 150, 105, 0.85)',
                hoverBackgroundColor: 'rgba(4, 120, 87, 1)',
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } }
            }
        }
    });
}

function downloadDataAnalisisExcel() {
    if (!chartDataMySQL || !chartDataMySQL.labelCabang || chartDataMySQL.labelCabang.length === 0) {
        alert('Belum ada data cabang untuk diexport.');
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,Laporan Peringkat Omzet Cabang Mau Teh-ko\n\n";
    csvContent += "Peringkat,Nama Cabang,Total Omzet (Rp)\n";

    let dataRekap = [];
    for (let i = 0; i < chartDataMySQL.labelCabang.length; i++) {
        dataRekap.push({
            nama: chartDataMySQL.labelCabang[i],
            omzet: chartDataMySQL.omzetCabang[i]
        });
    }
    
    dataRekap.sort((a, b) => b.omzet - a.omzet);

    dataRekap.forEach((item, index) => {
        csvContent += `${index + 1},"${item.nama}",${item.omzet}\n`; 
    });

    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    
    let date = new Date();
    let d = date.getDate().toString().padStart(2, '0');
    let m = (date.getMonth() + 1).toString().padStart(2, '0');
    let y = date.getFullYear();
    
    link.setAttribute("download", `Laporan_Omzet_Pusat_${y}${m}${d}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}