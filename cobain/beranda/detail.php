<?php
session_start();

// --- 1. KONEKSI DATABASE ---
include_once("test.php"); 
// --- 2. AMBIL ID PRODUK DARI URL ---
// detail.php?id=5
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- 3. QUERY DATA PRODUK ---
$query = "SELECT p.*, c.name AS category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.product_id = $id_produk";

$result = mysqli_query($mysqli, $query);
$produk = mysqli_fetch_assoc($result);

// Jika produk tidak ditemukan / ID salah
if (!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='../beranda/beranda.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk['name']) ?> - netofffice</title>
    <link rel="stylesheet" href="detail.css">
    <script src="beranda.js"></script>
</head>
<body>

    <div class="container">
        <!-- Tombol Kembali -->
        <div class="top-nav">
            <a href="../beranda/beranda.php" class="btn-back">← Kembali ke Beranda</a>
        </div>

        <div class="detail-wrapper">
            
            <!-- 1. BAGIAN GAMBAR -->
            <div class="detail-image">
                <?php 
                    $imgSource = "../uploads/" . $produk['image'];
                    // Cek ketersediaan gambar
                    if (!empty($produk['image']) && file_exists("../uploads/" . $produk['image'])) {
                        $displayImg = $imgSource;
                    } else {
                        // Placeholder jika tidak ada gambar
                        $displayImg = "https://placehold.co/600x600?text=" . urlencode($produk['name']);
                    }
                ?>
                <img src="<?= $displayImg ?>" alt="<?= htmlspecialchars($produk['name']) ?>">
            </div>

            <!-- 2. BAGIAN INFORMASI -->
            <div class="detail-info">
                <span class="category-badge"><?= htmlspecialchars($produk['category_name']) ?></span>
                
                <h1><?= htmlspecialchars($produk['name']) ?></h1>
                <div class="brand">Merk: <?= htmlspecialchars($produk['brand']) ?></div>

                <div class="price">Rp <?= number_format($produk['price'], 0, ',', '.') ?></div>

                <?php if ($produk['stock'] > 0): ?>
                    <div class="stock-status">✅ Stok Tersedia: <?= $produk['stock'] ?> unit</div>
                <?php else: ?>
                    <div class="stock-status stock-habis">❌ Stok Habis</div>
                <?php endif; ?>

                <div class="specs-box">
                    <h3>Deskripsi & Spesifikasi</h3>
                    <div class="specs-content"><?= htmlspecialchars($produk['specifications']) ?></div>
                </div>

                <div class="action-buttons">
                    <?php if ($produk['stock'] > 0): ?>
                        <button class="btn btn-cart" onclick="addToCart(<?= $produk['product_id'] ?>)">+ Keranjang</button>
                        <button class="btn btn-buy" onclick="alert('Fitur Checkout segera hadir!')">Beli Sekarang</button>
                    <?php else: ?>
                        <button class="btn" style="background:#ccc; cursor:not-allowed;" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        function addToCart(id) {
            // Di sini nanti logika AJAX ke keranjang
            alert("Produk berhasil ditambahkan ke keranjang!");
        }
    </script>

</body>
</html>