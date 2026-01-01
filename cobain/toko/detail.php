<?php
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SEWS - Detail Produk</title>

  <!-- CSS -->
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="detail.css" />
</head>
<body>

<header class="topbar">
  <div class="brand" onclick="location.href='index.php'" style="cursor:pointer">SEWS</div>
  <div class="cart" title="Keranjang">
    <span class="cart-ico">🛒</span>
    <span id="cartCount" class="cart-count">0</span>
  </div>
</header>

<main class="detail-wrap">
  <div class="product-box">
    <div id="detailRoot"></div>
  </div>
</main>

<!-- (opsional) kirim id produk dari PHP ke JS -->
<script>
  window.PRODUCT_ID = <?= json_encode($productId) ?>;
</script>

<!-- DATA -->
<script src="data.js"></script>
<!-- LOGIC DETAIL -->
<script src="detail.js"></script>

</body>
</html>
