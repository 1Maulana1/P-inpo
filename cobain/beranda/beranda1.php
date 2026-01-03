<?php
session_start();
include_once("../test.php"); // SESUAIKAN PATH

$isLoggedIn = isset($_SESSION['nama']);

/* ambil data produk */
$queryProduk = mysqli_query($mysqli, "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>netofffice - Beranda</title>
    <link rel="stylesheet" href="beranda.css">
</head>
<body>

<!-- ================= TOP BAR ================= -->
<div class="top-bar">
    <div class="container">
        <span>netofffice · B2B Elektronik Kantor</span>
        <div style="float:right">
            <?php if ($isLoggedIn): ?>
                Halo, <a href="../profil/profile.php"><?= htmlspecialchars($_SESSION['nama']) ?></a> |
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="../login/signup/signup.php">Daftar</a> |
                <a href="../login/login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ================= HEADER ================= -->
<header class="main-header">
    <div class="container header-content">
        <h1 class="logo">netofffice</h1>
        <input type="text" placeholder="Cari produk elektronik kantor...">
        <a href="../keranjang/keranjang.php">🛒 Keranjang</a>
    </div>
</header>

<!-- ================= PRODUK ================= -->
<main class="main-content">
    <div class="container">

        <h2 class="section-title">📦 Produk Elektronik Kantor</h2>

        <div class="product-grid">

            <?php if (mysqli_num_rows($queryProduk) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($queryProduk)): ?>
                    <div class="product-card">

                        <img 
                            src="../uploads/<?= $p['image'] ?: 'no-image.png' ?>" 
                            alt="<?= htmlspecialchars($p['name']) ?>"
                        >

                        <h3><?= htmlspecialchars($p['name']) ?></h3>

                        <p class="category">
                            <?= $p['category_name'] ?? 'Tanpa Kategori' ?>
                        </p>

                        <p class="price">
                            Rp <?= number_format($p['price'], 0, ',', '.') ?>
                        </p>

                        <p class="stock">
                            Stok: <?= $p['stock'] ?>
                        </p>

                        <a class="btn-detail"
                           href="../produk/detail.php?id=<?= $p['product_id'] ?>">
                           Lihat Detail
                        </a>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada produk.</p>
            <?php endif; ?>

        </div>

    </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>&copy; 2025 netofffice. All rights reserved.</p>
</footer>

</body>
</html>
