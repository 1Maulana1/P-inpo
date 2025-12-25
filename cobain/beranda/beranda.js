// ================= STATE =================
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let selectedProduct = null;
let currentBannerIndex = 0;

// ================= INITIALIZATION =================
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    initBannerCarousel();
    renderFlashSaleProducts();
    renderProducts();
});

const electronicsKeywords = ['laptop','printer','tinta','hp','handphone','phone','monitor','display','keyboard','mouse','headset','earphone','router','network','jaringan','ssd','hdd'];

function getElectronicsProducts() {
    if (typeof products === 'undefined') return [];
    return products.filter(p => {
        const name = p.name.toLowerCase();
        const desc = (p.desc || '').toLowerCase();
        return electronicsKeywords.some(k => name.includes(k) || desc.includes(k));
    });
}

// ================= CART FUNCTIONS =================
function updateCartCount() {
    const cartCountEl = document.getElementById('cartCount');
    if (cartCountEl) {
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartCountEl.textContent = totalItems;
    }
}

function addToCart() {
    if (!selectedProduct) return;
    
    const existingItem = cart.find(item => item.id === selectedProduct.id);
    
    if (existingItem) {
        existingItem.quantity = (existingItem.quantity || 1) + 1;
    } else {
        cart.push({
            ...selectedProduct,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    closeProductModal();
    
    showNotification('success', 'Berhasil!', `${selectedProduct.name} ditambahkan ke keranjang`);
}

function buyNow() {
    addToCart();
    window.location.href = '../keranjang/keranjang.html';
}

// ================= BANNER CAROUSEL =================
function initBannerCarousel() {
    const slides = document.querySelectorAll('.banner-slide');
    const dotsContainer = document.getElementById('bannerDots');
    
    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.className = `banner-dot ${index === 0 ? 'active' : ''}`;
        dot.onclick = () => goToSlide(index);
        dotsContainer.appendChild(dot);
    });
    
    // Auto slide
    setInterval(() => {
        currentBannerIndex = (currentBannerIndex + 1) % slides.length;
        goToSlide(currentBannerIndex);
    }, 4000);
}

function goToSlide(index) {
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.banner-dot');
    
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
    
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
    
    currentBannerIndex = index;
}

// ================= RENDER FUNCTIONS =================
function renderFlashSaleProducts() {
    const container = document.getElementById('flashSaleProducts');
    if (!container || typeof products === 'undefined') return;
    
    const flashSaleItems = getElectronicsProducts()
        .sort(() => Math.random() - 0.5)
        .slice(0, 4);

    if (flashSaleItems.length === 0) {
        container.innerHTML = '<p style="text-align:center;color:#666;grid-column:1/-1;padding:24px;">Produk elektronik belum tersedia.</p>';
        return;
    }
    
    container.innerHTML = flashSaleItems.map(product => {
        const discountPercent = Math.floor(Math.random() * 30) + 20; // 20-50% discount
        const originalPrice = Math.floor(product.price * (100 / (100 - discountPercent)));
        
        return `
            <div class="product-card" onclick="openProductModal(${product.id})">
                <img src="${product.img}" alt="${product.name}">
                <div class="product-info">
                    <h4>${product.name}</h4>
                    <div class="product-price">
                        ${formatRupiah(product.price)}
                        <span class="product-original-price">${formatRupiah(originalPrice)}</span>
                    </div>
                    <span class="product-discount">Rekomendasi</span>
                </div>
            </div>
        `;
    }).join('');
}

function renderStores() {
    const container = document.getElementById('storesList');
    if (!container || typeof stores === 'undefined') return;
    
    const storeIds = Object.keys(stores);
    
    container.innerHTML = storeIds.map(storeId => {
        const store = stores[storeId];
        return `
            <div class="store-card" onclick="goToStorePage('${storeId}')">
                <div class="store-avatar">🏪</div>
                <div class="store-info">
                    <h4>${store.name}</h4>
                    <p>${store.active}</p>
                    <span class="store-rating">⭐ ${store.rating.split(' ')[0]}</span>
                </div>
            </div>
        `;
    }).join('');
}

function renderProducts(filteredProducts = null) {
    const container = document.getElementById('productList');
    if (!container || typeof products === 'undefined') return;
    
    const baseProducts = getElectronicsProducts();
    const productsToRender = filteredProducts || baseProducts;
    
    if (productsToRender.length === 0) {
        container.innerHTML = '<p style="text-align:center;color:#666;grid-column:1/-1;padding:40px;">Tidak ada produk ditemukan.</p>';
        return;
    }
    
    container.innerHTML = productsToRender.map(product => {
        const store = stores[product.storeId];
        return `
            <div class="product-card" onclick="openProductModal(${product.id})">
                <img src="${product.img}" alt="${product.name}">
                <div class="product-info">
                    <h4>${product.name}</h4>
                    <p class="product-store">🏪 ${store ? store.name : 'Unknown Store'}</p>
                    <div class="product-price">${formatRupiah(product.price)}</div>
                </div>
            </div>
        `;
    }).join('');
}

// ================= SEARCH FUNCTION =================
function searchProduct() {
    const searchInput = document.getElementById('searchInput');
    const query = searchInput.value.toLowerCase().trim();
    const titleEl = document.getElementById('productTitle');
    const baseProducts = getElectronicsProducts();
    
    if (query === '') {
        titleEl.textContent = '📦 Produk Elektronik Kantor';
        renderProducts(baseProducts);
        return;
    }
    
    const filtered = baseProducts.filter(product => 
        product.name.toLowerCase().includes(query) ||
        product.desc.toLowerCase().includes(query)
    );
    
    titleEl.textContent = `🔍 Hasil pencarian: "${query}"`;
    renderProducts(filtered);
}

// ================= CATEGORY FILTER =================
function filterByCategory(category) {
    const titleEl = document.getElementById('productTitle');
    const baseProducts = getElectronicsProducts();
    let filtered = [];
    
    switch(category) {
        case 'laptop':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('laptop'));
            break;
        case 'printer':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('printer') || p.name.toLowerCase().includes('tinta'));
            break;
        case 'mobile':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('hp') || p.name.toLowerCase().includes('handphone') || p.name.toLowerCase().includes('phone'));
            break;
        case 'monitor':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('monitor') || p.name.toLowerCase().includes('display'));
            break;
        case 'peripheral':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('keyboard') || p.name.toLowerCase().includes('mouse'));
            break;
        case 'network':
            filtered = baseProducts.filter(p => p.name.toLowerCase().includes('router') || p.name.toLowerCase().includes('jaringan') || p.name.toLowerCase().includes('network'));
            break;
        default:
            filtered = baseProducts;
    }
    
    titleEl.textContent = `📂 Kategori: ${category.charAt(0).toUpperCase() + category.slice(1)}`;
    renderProducts(filtered);
    
    // Scroll to products section
    document.querySelector('.products-section').scrollIntoView({ behavior: 'smooth' });
}

function scrollToProducts(category) {
    filterByCategory(category);
}

// ================= MODAL FUNCTIONS =================
function openProductModal(productId) {
    selectedProduct = products.find(p => p.id === productId);
    if (!selectedProduct) return;
    
    const store = stores[selectedProduct.storeId];
    
    document.getElementById('modalProductImg').src = selectedProduct.img;
    document.getElementById('modalProductName').textContent = selectedProduct.name;
    document.getElementById('modalStoreName').textContent = store ? store.name : 'Unknown Store';
    document.getElementById('modalProductPrice').textContent = formatRupiah(selectedProduct.price);
    document.getElementById('modalProductDesc').textContent = selectedProduct.desc;
    
    document.getElementById('productModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
    document.body.style.overflow = '';
    selectedProduct = null;
}

function goToStore() {
    if (selectedProduct) {
        goToStorePage(selectedProduct.storeId);
    }
}

function goToStorePage(storeId) {
    window.location.href = `../toko/toko.html?id=${storeId}`;
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('productModal');
    if (e.target === modal) {
        closeProductModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductModal();
    }
});

// ================= NOTIFICATION SYSTEM =================
function showNotification(type, title, message) {
    const container = document.getElementById('notifikasiContainer');
    if (!container) return;
    
    const notification = document.createElement('div');
    notification.className = `notifikasi-item ${type}`;
    notification.innerHTML = `
        <div class="notifikasi-header">
            <span class="notifikasi-icon">${getNotificationIcon(type)}</span>
            <span class="notifikasi-title">${title}</span>
            <span class="notifikasi-close" onclick="this.parentElement.parentElement.remove()">×</span>
        </div>
        <div class="notifikasi-body">
            <p>${message}</p>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('removing');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function getNotificationIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️',
        promo: '🎉'
    };
    return icons[type] || 'ℹ️';
}

// ================= ADDITIONAL NOTIFICATION STYLES =================
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notifikasi-item {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid #FF5722;
        animation: slideIn 0.3s ease-out;
    }

    .notifikasi-item:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        transform: translateX(-5px);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(400px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notifikasi-item.removing {
        animation: slideOut 0.3s ease-out;
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    .notifikasi-item.success { border-left-color: #4CAF50; }
    .notifikasi-item.error { border-left-color: #F44336; }
    .notifikasi-item.warning { border-left-color: #FF9800; }
    .notifikasi-item.info { border-left-color: #2196F3; }
    .notifikasi-item.promo { border-left-color: #9C27B0; }

    .notifikasi-header {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        gap: 8px;
    }

    .notifikasi-icon { font-size: 18px; }
    
    .notifikasi-title {
        flex: 1;
        font-weight: 600;
        font-size: 14px;
    }

    .notifikasi-close {
        font-size: 20px;
        color: #999;
        cursor: pointer;
        padding: 0 4px;
    }

    .notifikasi-close:hover { color: #333; }

    .notifikasi-body {
        padding: 12px 16px;
    }

    .notifikasi-body p {
        font-size: 13px;
        color: #666;
        margin: 0;
    }
`;
document.head.appendChild(notificationStyles);
