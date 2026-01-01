<?php
// search.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SEWS - Search</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header class="topbar">
  <div class="brand" onclick="location.href='index.php'" style="cursor:pointer">SEWS</div>

  <div class="search-wrap">
    <input
      type="text"
      id="searchInput"
      placeholder="Cari Product"
      oninput="searchProduct()"
    />
    <button class="search-btn" type="button" onclick="searchProduct()" aria-label="search">
      🔍
    </button>
  </div>

  <div class="cart" title="Keranjang">
    <span class="cart-ico">🛒</span>
    <span id="cartCount" class="cart-count">0</span>
  </div>
</header>

<main class="container">
  <h2 id="hintText" class="hint">Silakan cari produk</h2>
  <div id="productList" class="grid"></div>
</main>

<script src="data.js"></script>
<script src="search.js"></script>
</body>
</html>
