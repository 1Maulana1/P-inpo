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
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; color: #333; }
        .container { max-width: 1100px; margin: 30px auto; padding: 20px; }
        
        /* Breadcrumb / Navigasi Atas */
        .top-nav { margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: #0056b3; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; }
        .btn-back:hover { text-decoration: underline; }

        /* Layout Detail */
        .detail-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            flex-wrap: wrap;
            overflow: hidden;
        }

        /* Kolom Kiri (Gambar) */
        .detail-image {
            flex: 1;
            min-width: 400px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #eee;
            padding: 20px;
        }
        .detail-image img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Kolom Kanan (Info) */
        .detail-info {
            flex: 1;
            min-width: 400px;
            padding: 40px;
        }

        .category-badge {
            background-color: #e3f2fd;
            color: #0056b3;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        h1 { margin: 15px 0 10px 0; font-size: 28px; color: #222; }
        .brand { font-size: 16px; color: #666; margin-bottom: 20px; }
        
        .price { font-size: 32px; color: #0056b3; font-weight: bold; margin-bottom: 20px; }
        
        .stock-status {
            display: inline-block;
            margin-bottom: 20px;
            padding: 5px 0;
            font-weight: bold;
            color: #28a745;
        }
        .stock-habis { color: #dc3545; }

        .specs-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }
        .specs-box h3 { margin-top: 0; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .specs-content { line-height: 1.6; color: #555; white-space: pre-line; /* Agar enter terbaca */ }

        /* Actions */
        .action-buttons { display: flex; gap: 15px; }
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            flex: 1;
            transition: 0.2s;
        }
        .btn-cart { background-color: #28a745; color: white; }
        .btn-cart:hover { background-color: #218838; }
        .btn-buy { background-color: #0056b3; color: white; }
        .btn-buy:hover { background-color: #004494; }

        @media (max-width: 768px) {
            .detail-wrapper { flex-direction: column; }
            .detail-image, .detail-info { min-width: 100%; border-right: none; }
            .detail-image { border-bottom: 1px solid #eee; }
        }
    </style>
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