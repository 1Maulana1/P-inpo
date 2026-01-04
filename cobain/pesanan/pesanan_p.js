// 1. DATA PESANAN
// Diambil dari variabel global window.ordersData yang diset oleh PHP
const orders = window.ordersData || [];

// Data Status Tab
const tabs = [
    { id: 'all', label: 'Semua' },
    { id: 'unpaid', label: 'Belum Bayar' },
    { id: 'shipping', label: 'Dikirim' },
    { id: 'completed', label: 'Selesai' },
    { id: 'cancelled', label: 'Dibatalkan' }
];

let activeTab = 'all';

// Format Rupiah
const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

// Render Tabs (Tombol Navigasi Status)
function renderTabs() {
    const container = document.getElementById('tabs-container');
    container.innerHTML = tabs.map(tab => `
        <button onclick="setActiveTab('${tab.id}')" 
            class="tab-button ${activeTab === tab.id ? 'active' : ''}">
            ${tab.label}
        </button>
    `).join('');
}

// Fungsi Ganti Tab
window.setActiveTab = (tabId) => {
    activeTab = tabId;
    renderTabs();
    renderOrders();
};

// Render Daftar Pesanan (Card)
function renderOrders() {
    const listContainer = document.getElementById('orders-list');
    
    // Filter data berdasarkan tab aktif
    const filteredOrders = activeTab === 'all' 
        ? orders 
        : orders.filter(o => o.statusCode === activeTab);

    // Tampilan jika kosong
    if (filteredOrders.length === 0) {
        listContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                   </svg>
                </div>
                <p class="empty-text">Belum ada pesanan di status ini</p>
            </div>
        `;
        return;
    }

    // Tampilan list pesanan
    listContainer.innerHTML = filteredOrders.map(order => `
        <div class="order-card">
            <div class="order-header">
                <div class="order-header-left">
                    <span class="shop-name">${order.shopName}</span>
                    <span style="color:#aaa; font-size:12px; margin-left:5px;">(${order.id})</span>
                </div>
                <div class="order-status ${order.statusCode === 'completed' ? 'completed' : ''}">
                    ${order.statusLabel.toUpperCase()}
                </div>
            </div>

            <div>
                ${order.items.map(item => `
                    <div class="order-item">
                        <img src="${item.image}" class="item-image" alt="${item.name}">
                        <div class="item-details">
                            <div>
                                <h3 class="item-name">${item.name}</h3>
                                <p class="item-variant">${item.variant ? 'Brand: ' + item.variant : ''}</p>
                            </div>
                            <div class="item-footer">
                                <p class="item-quantity">x${item.quantity}</p>
                                <div class="item-price-wrapper">
                                    <span class="item-price">${formatRupiah(item.price)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>

            <div class="order-footer">
                <div class="order-total">
                    <span class="order-total-label">Total Pesanan:</span>
                    <span class="order-total-amount">${formatRupiah(order.total)}</span>
                </div>
                
                <div class="order-actions">
                    ${order.statusCode === 'completed' ? `
                        <button class="btn-primary" onclick="alert('Fitur Beli Lagi belum tersedia')">Beli Lagi</button>
                    ` : ''}
                    
                    ${order.statusCode === 'shipping' ? `
                        <button class="btn-primary" onclick="terimaPesanan(${order.real_id})">Pesanan Diterima</button>
                        <button class="btn-secondary">Lacak</button>
                    ` : ''}

                    ${order.statusCode === 'unpaid' ? `
                        <button class="btn-primary" onclick="alert('Silakan transfer manual ke BCA')">Bayar Sekarang</button>
                    ` : ''}
                    
                    <button class="btn-link">Hubungi Penjual</button>
                </div>
            </div>
        </div>
    `).join('');
}

// Fungsi Terima Pesanan (Simulasi AJAX)
window.terimaPesanan = (id) => {
    if(confirm("Apakah Anda yakin sudah menerima pesanan ini?")) {
        alert("Status pesanan ID " + id + " berhasil diupdate! (Simulasi)");
        // Di sini nanti tempat fetch ke PHP untuk update status
    }
}

// Jalankan saat halaman siap
document.addEventListener('DOMContentLoaded', () => {
    renderTabs();
    renderOrders();
});