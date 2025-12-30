// JS moved from inline <script> in checkout.html
// Data Dasar
const subtotalProduk = 250000;
const biayaLayanan = 1000;
let shippingCost = 15000;

// Element Referensi
const shippingSelect = document.getElementById('shipping-method');
const shippingDisplay = document.getElementById('shipping-display');
const shippingSummary = document.getElementById('shipping-summary');
const grandTotalDisplay = document.getElementById('grand-total');
const stickyTotal = document.getElementById('sticky-total');
const stickyTotalMobile = document.getElementById('sticky-total-mobile');
const modal = document.getElementById('success-modal');

// Fungsi Format Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
}

// Fungsi Update Total
function updateTotal() {
    const total = subtotalProduk + shippingCost + biayaLayanan;
    const formattedTotal = formatRupiah(total);
    const formattedShipping = formatRupiah(shippingCost);

    // Update UI Text
    if (shippingDisplay) shippingDisplay.textContent = formattedShipping;
    if (shippingSummary) shippingSummary.textContent = formattedShipping;
    if (grandTotalDisplay) grandTotalDisplay.textContent = formattedTotal;
    if (stickyTotal) stickyTotal.textContent = formattedTotal;
    if (stickyTotalMobile) stickyTotalMobile.textContent = formattedTotal;
}

// Event Listener saat opsi pengiriman berubah
if (shippingSelect) {
    shippingSelect.addEventListener('change', (e) => {
        shippingCost = parseInt(e.target.value);
        updateTotal();
    });
}

// Event Listener Tombol Buat Pesanan
function placeOrder() {
    if (!modal) return;
    modal.classList.remove('hidden');
    setTimeout(() => {
        const content = document.getElementById('modal-content');
        if (content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
}

function closeModal() {
    if (!modal) return;
    modal.classList.add('hidden');
    const content = document.getElementById('modal-content');
    if (content) {
        content.classList.add('scale-95');
        content.classList.remove('scale-100');
    }
    window.scrollTo(0,0);
}

// Expose placeOrder/closeModal to global scope so HTML onclick attributes work
window.placeOrder = placeOrder;
window.closeModal = closeModal;

// Inisialisasi awal
document.addEventListener('DOMContentLoaded', updateTotal);
