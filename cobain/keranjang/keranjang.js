let cartItems = [
    {
        id: 1,
        name: "Laptop UltraBook X1",
        price: 15000000,
        quantity: 1,
        image: "https://placehold.co/100x100/e5e5e5/5AA9E6?text=Laptop"
    },
    {
        id: 2,
        name: "Headphone Nirkabel Pro",
        price: 2500000,
        quantity: 2,
        image: "https://placehold.co/100x100/e5e5e5/5AA9E6?text=Headphone"
    },
    {
        id: 3,
        name: "Mouse Gaming RGB",
        price: 500000,
        quantity: 1,
        image: "https://placehold.co/100x100/e5e5e5/5AA9E6?text=Mouse"
    },
    {
        id: 4,
        name: "Mouse Gaming RGB",
        price: 500000,
        quantity: 1,
        image: "https://placehold.co/100x100/e5e5e5/5AA9E6?text=Mouse"
    }
];

const SHIPPING_COST = 15000;

const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
});

function formatPrice(value) {
    return formatter.format(value);
}

function renderCartItems() {
    const container = document.getElementById('cart-items-container');
    
    if (cartItems.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <span>🛒</span>
                <p>Keranjang belanja kosong</p>
            </div>
        `;
        return;
    }

    container.innerHTML = cartItems.map(item => `
        <div class="cart-item">
            <img src="${item.image}" alt="${item.name}">
            
            <div class="item-info">
                <h3>${item.name}</h3>
                <p class="price">${formatPrice(item.price)}</p>
                <button class="btn-remove" onclick="removeItem(${item.id})">Hapus</button>
            </div>

            <div class="item-controls">
                <div class="qty-control">
                    <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">−</button>
                    <span class="qty-value">${item.quantity}</span>
                    <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                </div>
                <p class="item-total">${formatPrice(item.price * item.quantity)}</p>
            </div>
        </div>
    `).join('');
}

function updateSummary() {
    let subtotal = 0;
    let totalItems = 0;

    cartItems.forEach(item => {
        subtotal += item.price * item.quantity;
        totalItems += item.quantity;
    });

    const shipping = cartItems.length > 0 ? SHIPPING_COST : 0;

    document.getElementById('total-item').textContent = totalItems;
    document.getElementById('summary-item-count').textContent = totalItems;
    document.getElementById('cart-subtotal').textContent = formatPrice(subtotal);
    document.getElementById('summary-subtotal').textContent = formatPrice(subtotal);
    document.getElementById('shipping-cost').textContent = formatPrice(shipping);
    document.getElementById('order-total').textContent = formatPrice(subtotal + shipping);
}

function updateQuantity(id, delta) {
    const item = cartItems.find(i => i.id === id);
    if (!item) return;

    item.quantity += delta;
    if (item.quantity <= 0) {
        removeItem(id);
        return;
    }

    renderCartItems();
    updateSummary();
}

function removeItem(id) {
    cartItems = cartItems.filter(item => item.id !== id);
    renderCartItems();
    updateSummary();
}

document.addEventListener('DOMContentLoaded', () => {
    renderCartItems();
    updateSummary();
});
