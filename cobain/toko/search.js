const productList = document.getElementById("productList");
const hintText = document.getElementById("hintText");
const cartCount = document.getElementById("cartCount");

function searchProduct(){
  const key = document.getElementById("searchInput").value.trim().toLowerCase();

  // awal kosong kalau belum ketik
  if(!key){
    hintText.style.display = "block";
    productList.innerHTML = "";
    return;
  }

  hintText.style.display = "none";
  const filtered = products.filter(p => p.name.toLowerCase().includes(key));

  if(filtered.length === 0){
    productList.innerHTML = `<p style="grid-column:1/-1;margin:0;">Produk tidak ditemukan.</p>`;
    return;
  }

  productList.innerHTML = filtered.map(p => {
    const storeName = stores[p.storeId]?.name || "Toko";
    return `
      <div class="card" onclick="goToDetail(${p.id})">
        <img src="${p.img}" alt="${p.name}">
        <div class="pbody">
          <h4>${p.name}</h4>

          <div class="store-link"
               onclick="event.stopPropagation(); goToStore('${p.storeId}')">
            ${storeName}
          </div>

          <div class="price">${formatRupiah(p.price)}</div>
        </div>
      </div>
    `;
  }).join("");
}

function goToStore(storeId){
  window.location.href = `toko.html?store=${encodeURIComponent(storeId)}`;
}

function goToDetail(productId){
  window.location.href = `detail.html?id=${encodeURIComponent(productId)}`;
}

// badge keranjang
function updateCartBadge(){
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  cartCount.textContent = cart.length;
}
updateCartBadge();

// kalau mau tombol reset, panggil dari tombol saja (jangan auto)
function resetCart(){
  localStorage.removeItem("cart");
  updateCartBadge(); // ✅ bukan updateCartCount()
}
