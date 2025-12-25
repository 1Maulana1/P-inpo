const params = new URLSearchParams(window.location.search);
const storeId = params.get("store");

const cartCount = document.getElementById("cartCount");

const storeNameEl = document.getElementById("storeName");
const storeActiveEl = document.getElementById("storeActive");

const storeProductsCountEl = document.getElementById("storeProductsCount");
const storeFollowingEl = document.getElementById("storeFollowing");
const storeChatPerfEl = document.getElementById("storeChatPerf");
const storeFollowersEl = document.getElementById("storeFollowers");
const storeRatingEl = document.getElementById("storeRating");
const storeJoinedEl = document.getElementById("storeJoined");

const storeProductList = document.getElementById("storeProductList");

let storeProducts = [];

function loadStore(){
  if(!storeId || !stores[storeId]){
    storeNameEl.textContent = "Toko tidak ditemukan";
    return;
  }

  const s = stores[storeId];
  storeNameEl.textContent = s.name;
  storeActiveEl.textContent = s.active;

  storeProducts = products.filter(p => p.storeId === storeId);

  storeProductsCountEl.textContent = storeProducts.length;
  storeFollowingEl.textContent = s.following;
  storeChatPerfEl.textContent = s.chatPerf;
  storeFollowersEl.textContent = s.followers;
  storeRatingEl.textContent = s.rating;
  storeJoinedEl.textContent = s.joined;

  renderStoreProducts(storeProducts);
}

function renderStoreProducts(list){
  if(list.length === 0){
    storeProductList.innerHTML = `<p style="grid-column:1/-1;margin:0;">Produk toko ini tidak ada.</p>`;
    return;
  }

  storeProductList.innerHTML = list.map(p => `
    <div class="card" onclick="goToDetail(${p.id})">
      <img src="${p.img}" alt="${p.name}">
      <div class="pbody">
        <h4>${p.name}</h4>
        <div class="price">${formatRupiah(p.price)}</div>
      </div>
    </div>
  `).join("");
}


function searchInStore(){
  const key = document.getElementById("storeSearch").value.trim().toLowerCase();
  if(!key){
    renderStoreProducts(storeProducts);
    return;
  }
  const filtered = storeProducts.filter(p => p.name.toLowerCase().includes(key));
  renderStoreProducts(filtered);
}

function goToDetail(productId){
  window.location.href = `detail.html?id=${encodeURIComponent(productId)}`;
}

function updateCartBadge(){
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  cartCount.textContent = cart.length;
}

updateCartBadge();
loadStore();
