<?php
session_start();

// 1. KONEKSI DATABASE
// Menggunakan path relatif '../' karena file ini ada di folder 'keranjang/'
include_once("test.php");

// 2. LOGIKA KERANJANG
$cartItems = [];
$totalBelanja = 0;
$totalQty = 0;

if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
    $ids = array_keys($_SESSION['keranjang']);
    $ids_string = implode(',', $ids);

    if (!empty($ids_string)) {
        $query = "SELECT * FROM products WHERE product_id IN ($ids_string)";
        $result = mysqli_query($mysqli, $query); // Pakai $mysqli yang sudah didefinisikan

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['product_id'];
            $qty = $_SESSION['keranjang'][$id];
            
            $subtotal = $row['price'] * $qty;
            
            $row['qty'] = $qty;
            $row['subtotal'] = $subtotal;
            $cartItems[] = $row;

            $totalBelanja += $subtotal;
            $totalQty += $qty;
        }
    }
}

$isLoggedIn = isset($_SESSION['nama']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - netofffice</title>
    <link rel="stylesheet" href="keranjang.css">
    <style>

    </style>
</head>
<body>

    <header>
        <div class="top-header"> 
            <div class="top-left"><span>netofffice · B2B Elektronik Kantor</span></div>
            <div class="top-right">
                <?php if ($isLoggedIn): ?>
                    <a href="../profil/profile.php">Halo, <?= htmlspecialchars($_SESSION['nama']) ?></a> <span>|</span> <a href="../logout.php">Logout</a>
                <?php else: ?>
                    <a href="../login/signup/signup.php">Daftar</a> <span>|</span> <a href="../login/login.php">Log In</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="main-header">
            <div class="logo"><a class="home" href="../beranda/beranda.php">netofffice</a></div>
            <div class="search-box"> 
                <input type="text" placeholder="Cari elektronik kantor">
                <button> 🔍 </button>
            </div>
            <div class="cart-icon">🛒 <span style="font-size:12px; font-weight:bold; background:red; color:white; padding:2px 6px; border-radius:50%;"><?= $totalQty ?></span></div>
        </div>
    </header>

    <div class="container"> 
        <div class="content">
            <section class="cart-section">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <h2 style="margin: 0;">Keranjang Item (<?= $totalQty ?>)</h2>
                    <?php if (!empty($cartItems)): ?>
                        <a href="aksi_keranjang.php?act=clear" onclick="return confirm('Kosongkan keranjang?')" style="color:red; font-size:12px; text-decoration:none;">Kosongkan</a>
                    <?php endif; ?>
                </div>
                
                <div id="cart-items-container">
                    <?php if (empty($cartItems)): ?>
                        <div class="empty-cart">
                            <h3>Keranjang Anda kosong 😔</h3>
                            <p>Yuk cari barang kebutuhan kantor Anda!</p>
                            <a href="../beranda/beranda.php" style="color:#0056b3;">Mulai Belanja</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cartItems as $item): ?>
                            <?php 
                                $imgSource = "../uploads/" . $item['image'];
                                $displayImg = (!empty($item['image']) && file_exists($imgSource)) ? $imgSource : "https://placehold.co/100x100?text=No+Img";
                            ?>
                            <div class="cart-item">
                                <img src="<?= $displayImg ?>" class="cart-img" alt="<?= htmlspecialchars($item['name']) ?>">
                                
                                <div class="cart-details">
                                    <a href="../detail/detail.php?id=<?= $item['product_id'] ?>" class="cart-title"><?= htmlspecialchars($item['name']) ?></a>
                                    <div class="cart-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                    
                                    <!-- Bagian Update Qty -->
                                    <div class="cart-actions">
                                        <!-- Tombol Kurang -->
                                        <a href="aksi_keranjang.php?act=kurang&id=<?= $item['product_id'] ?>" class="btn-qty">-</a>
                                        
                                        <span style="min-width: 20px; text-align: center;"><?= $item['qty'] ?></span>
                                        
                                        <!-- Tombol Tambah -->
                                        <a href="aksi_keranjang.php?act=tambah&id=<?= $item['product_id'] ?>" class="btn-qty">+</a>

                                        <!-- Tombol Hapus -->
                                        <a href="aksi_keranjang.php?act=hapus&id=<?= $item['product_id'] ?>" class="btn-delete" onclick="return confirm('Hapus barang ini?')">🗑️ Hapus</a>
                                    </div>
                                    <div style="font-size:12px; color:#666; margin-top:8px;">
                                        Subtotal: Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cart-footer">
                    <a class="back-home" href="../beranda/beranda.php">← Lanjut Belanja</a>
                    <p class="subtotal"> Subtotal: <span> Rp <?= number_format($totalBelanja, 0, ',', '.') ?></span> </p>
                </div>
            </section>

            <!-- Sidebar Ringkasan -->
            <?php if (!empty($cartItems)): ?>
            <aside class="summary">
                <h2>Ringkasan Pesanan</h2>
                <div class="summary-row">
                    <span> Subtotal (<?= $totalQty ?> Items)</span>
                    <span> Rp <?= number_format($totalBelanja, 0, ',', '.') ?></span>
                </div>
                <div class="summary-row">
                    <span> Biaya pengiriman </span>
                    <span id="shipping-cost" style="color:green;">Gratis</span>
                </div>
                <div class="summary-total">
                    <span> Total </span>
                    <span>Rp <?= number_format($totalBelanja, 0, ',', '.') ?></span>
                </div>
                 <button class="btn-bayar" onclick="window.location.href='/cobain/checkout/checkout.php'"> Pembayaran </button>
            </aside>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>