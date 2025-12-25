
        // Data Status Tab
        const tabs = [
            { id: 'all', label: 'Semua' },
            { id: 'unpaid', label: 'Belum Bayar' },
            { id: 'shipping', label: 'Dikirim' },
            { id: 'completed', label: 'Selesai' },
            { id: 'cancelled', label: 'Dibatalkan' },
            { id: 'return', label: 'Pengembalian' }
        ];

        // Data Mock Pesanan
        const orders = [
            {
                id: 'ORD-20231025-001',
                shopName: 'Official Store Samsung',
                status: 'Selesai',
                statusCode: 'completed',
                statusLabel: 'Pesanan Selesai',
                items: [
                    {
                        name: 'Samsung Galaxy S23 Ultra 5G 256GB',
                        variant: 'Phantom Black',
                        quantity: 1,
                        price: 19999000,
                        originalPrice: 21999000,
                        image: 'https://placehold.co/80x80/222222/ffffff?text=S23'
                    }
                ],
                total: 20015000 // Termasuk ongkir
            },
            {
                id: 'ORD-20231024-055',
                shopName: 'Fesyen Wanita Murah',
                status: 'Dikirim',
                statusCode: 'shipping',
                statusLabel: 'Paket sedang dibawa kurir',
                items: [
                    {
                        name: 'Kemeja Oversize Wanita Korean Style',
                        variant: 'Putih, L',
                        quantity: 2,
                        price: 45000,
                        originalPrice: 85000,
                        image: 'https://placehold.co/80x80/ffafcc/ffffff?text=Kemeja'
                    },
                    {
                        name: 'Celana Kulot Highwaist Linen',
                        variant: 'Cream, XL',
                        quantity: 1,
                        price: 65000,
                        originalPrice: 100000,
                        image: 'https://placehold.co/80x80/fcd5ce/ffffff?text=Kulot'
                    }
                ],
                total: 167000
            },
            {
                id: 'ORD-20231020-999',
                shopName: 'Toko Buku Gramedia',
                status: 'Dibatalkan',
                statusCode: 'cancelled',
                statusLabel: 'Dibatalkan',
                items: [
                    {
                        name: 'Novel Laut Bercerita - Leila S. Chudori',
                        variant: 'Soft Cover',
                        quantity: 1,
                        price: 95000,
                        originalPrice: null,
                        image: 'https://placehold.co/80x80/1e293b/ffffff?text=Buku'
                    }
                ],
                total: 105000
            }
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

        // Render Tabs
        function renderTabs() {
            const container = document.getElementById('tabs-container');
            container.innerHTML = tabs.map(tab => `
                <button onclick="setActiveTab('${tab.id}')" 
                    class="tab-button ${activeTab === tab.id ? 'active' : ''}">
                    ${tab.label}
                </button>
            `).join('');
        }

        // Set Active Tab
        window.setActiveTab = (tabId) => {
            activeTab = tabId;
            renderTabs();
            renderOrders();
        };

        // Render Cards
        function renderOrders() {
            const listContainer = document.getElementById('orders-list');
            
            // Filter Orders
            const filteredOrders = activeTab === 'all' 
                ? orders 
                : orders.filter(o => o.statusCode === activeTab);

            if (filteredOrders.length === 0) {
                listContainer.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="empty-text">Belum ada pesanan</p>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = filteredOrders.map(order => `
                <div class="order-card">
                    <!-- Header Kartu: Toko & Status -->
                    <div class="order-header">
                        <div class="order-header-left">
                            <span class="shop-name">${order.shopName}</span>
                            <button class="btn-chat">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM21 9H3M21 15H3M12 3v18"/></svg>
                                Chat
                            </button>
                            <button class="btn-visit-shop">Kunjungi Toko</button>
                        </div>
                        <div class="order-status ${order.statusCode === 'completed' ? 'completed' : ''}">
                             <span class="status-divider"></span>
                            ${order.statusLabel.toUpperCase()}
                        </div>
                    </div>

                    <!-- List Produk -->
                    <div>
                        ${order.items.map(item => `
                            <div class="order-item">
                                <img src="${item.image}" class="item-image" alt="${item.name}">
                                <div class="item-details">
                                    <div>
                                        <h3 class="item-name">${item.name}</h3>
                                        <p class="item-variant">Variasi: ${item.variant}</p>
                                    </div>
                                    <div class="item-footer">
                                        <p class="item-quantity">x${item.quantity}</p>
                                        <div class="item-price-wrapper">
                                            ${item.originalPrice ? `<span class="item-price-original">${formatRupiah(item.originalPrice)}</span>` : ''}
                                            <span class="item-price">${formatRupiah(item.price)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>

                    <!-- Footer Kartu: Total & Aksi -->
                    <div class="order-footer">
                        <div class="order-total">
                            <span class="order-total-label">Total Pesanan:</span>
                            <span class="order-total-amount">${formatRupiah(order.total)}</span>
                        </div>
                        
                        <div class="order-actions">
                            ${order.statusCode === 'completed' ? `
                                <button class="btn-primary" onclick="buyAgain('${order.id}')">Beli Lagi</button>
                                <button class="btn-secondary">Nilai</button>
                            ` : ''}
                            
                            ${order.statusCode === 'shipping' ? `
                                <button class="btn-primary" onclick="confirmReceived('${order.id}')">Pesanan Diterima</button>
                                <button class="btn-secondary">Lacak</button>
                            ` : ''}

                            ${order.statusCode === 'cancelled' ? `
                                <button class="btn-primary" onclick="buyAgain('${order.id}')">Beli Lagi</button>
                                <button class="btn-secondary">Rincian Pembatalan</button>
                            ` : ''}
                            
                            <button class="btn-link">Hubungi Penjual</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Aksi: Beli Lagi
        window.buyAgain = (orderId) => {
            const order = orders.find(o => o.id === orderId);
            if (!order) return;
            alert(`Menambahkan ulang item dari pesanan ${orderId} ke keranjang.`);
        };

        // Aksi: Konfirmasi diterima
        window.confirmReceived = (orderId) => {
            const order = orders.find(o => o.id === orderId);
            if (!order) return;

            order.statusCode = 'completed';
            order.statusLabel = 'Pesanan Selesai';
            order.status = 'Selesai';
            renderOrders();
            alert(`Pesanan ${orderId} dikonfirmasi diterima.`);
        };

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            renderTabs();
            renderOrders();
        });

