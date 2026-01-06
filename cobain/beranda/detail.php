<?php
session_start();

// --- 1. KONEKSI DATABASE (ROBUST) ---
include_once("test.php");
// --- 2. AMBIL ID PRODUK ---
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- 3. QUERY DATA ---
$query = "SELECT p.*, c.name AS category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.product_id = $id_produk";

$result = mysqli_query($mysqli, $query);
$produk = mysqli_fetch_assoc($result);

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
    <!-- Pastikan CSS tersedia -->
    <link rel="stylesheet" href="detail.css"> 
    <style>
        /* CSS Inline Jaga-jaga jika detail.css belum ada */
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; }
        .container { max-width: 1100px; margin: 30px auto; padding: 20px; }
        .top-nav { margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: #0056b3; font-weight: bold; }
        
        .detail-wrapper { background: white; border-radius: 8px; display: flex; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .detail-image { flex: 1; background: #f9f9f9; padding: 20px; display: flex; align-items: center; justify-content: center; }
        .detail-image img { max-width: 100%; max-height: 500px; object-fit: contain; }
        
        .detail-info { flex: 1; padding: 40px; }
        .category-badge { background: #e3f2fd; color: #0056b3; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        h1 { margin: 15px 0 10px; font-size: 28px; }
        .price { font-size: 32px; color: #0056b3; font-weight: bold; margin: 20px 0; }
        
        .specs-box { background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 30px; border: 1px solid #eee; }
        .specs-content { white-space: pre-line; color: #555; line-height: 1.6; }
        
        .action-buttons { display: flex; gap: 15px; }
        .btn { padding: 15px 30px; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; flex: 1; color: white; transition: 0.2s; }
        .btn-cart { background: #28a745; }
        .btn-cart:hover { background: #218838; }
        .btn-buy { background: #0056b3; }
        .btn-buy:hover { background: #004494; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>

    <div class="container">
        <div class="top-nav">
            <a href="../beranda/beranda.php" class="btn-back">← Kembali ke Beranda</a>
        </div>

        <div class="detail-wrapper">
            
            <!-- GAMBAR -->
            <div class="detail-image">
                <?php 
                    $imgSource = "../uploads/" . $produk['image'];
                    if (!empty($produk['image']) && file_exists("../uploads/" . $produk['image'])) {
                        $displayImg = $imgSource;
                    } else {
                        $displayImg = "https://placehold.co/600x600?text=" . urlencode($produk['name']);
                    }
                ?>
                <img src="<?= $displayImg ?>" alt="<?= htmlspecialchars($produk['name']) ?>">
            </div>

            <!-- INFO -->
            <div class="detail-info">
                <span class="category-badge"><?= htmlspecialchars($produk['category_name']) ?></span>
                
                <h1><?= htmlspecialchars($produk['name']) ?></h1>
                <div class="brand">Merk: <?= htmlspecialchars($produk['brand']) ?></div>

                <div class="price">Rp <?= number_format($produk['price'], 0, ',', '.') ?></div>

                <?php if ($produk['stock'] > 0): ?>
                    <div style="color: green; font-weight: bold; margin-bottom: 20px;">✅ Stok Tersedia: <?= $produk['stock'] ?></div>
                <?php else: ?>
                    <div style="color: red; font-weight: bold; margin-bottom: 20px;">❌ Stok Habis</div>
                <?php endif; ?>

                <div class="specs-box">
                    <h3>Deskripsi & Spesifikasi</h3>
                    <div class="specs-content"><?= htmlspecialchars($produk['specifications']) ?></div>
                </div>

                <div class="action-buttons">
                    <?php if ($produk['stock'] > 0): ?>
                        <button class="btn btn-cart" onclick="addToCart(<?= $produk['product_id'] ?>)">+ Keranjang</button>
                        
                        <!-- UPDATE: Panggil fungsi buyNow -->
                        <button class="btn btn-buy" onclick="buyNow(<?= $produk['product_id'] ?>)">Beli Sekarang</button>
                    <?php else: ?>
                        <button class="btn" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- LOGIKA JAVASCRIPT -->
    <script>
        // Fungsi Tambah ke Keranjang (Tanpa Pindah Halaman)
        function addToCart(id) {
            fetch('../keranjang/aksi_keranjang.php?act=tambah&id=' + id)
            .then(response => {
                if(response.ok) {
                    alert("✅ Produk berhasil ditambahkan ke keranjang!");
                } else {
                    alert("Gagal menghubungi server.");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Fungsi Beli Sekarang (Tambah lalu Pindah ke Pembayaran)
        function buyNow(id) {
            // 1. Tambahkan ke keranjang dulu via AJAX
            fetch('../keranjang/aksi_keranjang.php?act=tambah&id=' + id)
            .then(response => {
                if(response.ok) {
                    // 2. Jika sukses, langsung redirect ke halaman checkout/pembayaran
                    // Pastikan path ini sesuai dengan struktur folder Anda
                    window.location.href = '../checkout/checkout.php';
                } else {
                    alert("Terjadi kesalahan saat memproses pesanan.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Gagal koneksi.");
            });
        }
    </script>

</body>
</html>