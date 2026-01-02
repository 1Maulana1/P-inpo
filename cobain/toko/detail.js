const cartCount = document.getElementById("cartCount");
const root = document.getElementById("detailRoot");

/* ===== CART BADGE ===== */
function updateCartBadge(){
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  cartCount.textContent = cart.length;
}
updateCartBadge();

/* ===== AMBIL ID PRODUK ===== */
const params = new URLSearchParams(window.location.search);
const id = Number(params.get("id"));

const product = products.find(p => p.id === id);

if(!product){
  root.innerHTML = "<p style='padding:24px'>Produk tidak ditemukan.</p>";
} else {
  const store = stores[product.storeId];
  const storeName = store?.name || "Toko";

  root.innerHTML = `
    <div class="detail-card">
      <div class="image-col">
        <img src="${product.img}" alt="${product.name}">
      </div>

      <div class="info-col">
        <h2 class="product-title">${product.name}</h2>

        <div class="store-name"
             onclick="location.href='toko.html?store=${encodeURIComponent(product.storeId)}'">
          ${storeName}
        </div>

        <div class="product-price">
          ${formatRupiah(product.price)}
        </div>

        <p class="product-desc">
          ${product.desc || "Deskripsi produk belum tersedia."}
        </p>

        <!-- TOMBOL AKSI -->
        <div class="btn-row btn-row-split">
          <button class="btn btn-soft" onclick="history.back()">Kembali</button>
          <button class="btn btn-primary" id="addCartBtn">+ Keranjang</button>
        </div>
      </div>
    </div>
  `;

  document.getElementById("addCartBtn").addEventListener("click", () => {
    addToCart(product.id);
  });
}

/* ===== ADD TO CART ===== */
function addToCart(productId){
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  cart.push(productId);
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartBadge();
  alert("Ditambahkan ke keranjang!");
}
