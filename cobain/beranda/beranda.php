<?php
session_start();

// --- KONEKSI DATABASE ---
$mysqli = null;
if (file_exists('test.php')) {
    include_once("test.php");
} elseif (file_exists('../koneksi.php')) {
    include_once("../koneksi.php");
    if (isset($koneksi)) $mysqli = $koneksi;
} else {
    // Fallback koneksi
    $mysqli = mysqli_connect("localhost", "root", "", "netofffice_db");
}

if (!$mysqli) {
    die("Koneksi gagal. Cek file koneksi.");
}

$isLoggedIn = isset($_SESSION['nama']);

// --- LOGIKA FILTER (Baru) ---
$whereClauses = [];

// 1. Filter Pencarian (Keyword)
$keyword = "";
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($mysqli, $_GET['keyword']);
    $whereClauses[] = "(p.name LIKE '%$keyword%' OR p.brand LIKE '%$keyword%')";
}

// 2. Filter Kategori (Dari URL ?category=...)
$selectedCategory = "";
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selectedCategory = mysqli_real_escape_string($mysqli, $_GET['category']);
    $whereClauses[] = "c.name LIKE '%$selectedCategory%'";
}

// Gabungkan Filter
$sqlWhere = "";
if (count($whereClauses) > 0) {
    $sqlWhere = "WHERE " . implode(' AND ', $whereClauses);
}

// --- QUERY UTAMA ---

// A. Query Rekomendasi (Stok Terbanyak, abaikan filter biar tetap muncul)
$queryRekomendasi = mysqli_query($mysqli, "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.stock DESC
    LIMIT 4
");

// B. Query Produk (Dengan Filter)
$queryProduk = mysqli_query($mysqli, "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    $sqlWhere
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>netofffice - Beranda</title>
    <link rel="stylesheet" href="beranda.css">
    
    <style>
        /* CSS Tambahan untuk Kategori Aktif */
        .category-link { text-decoration: none; color: inherit; display: block; }
        .category-item { cursor: pointer; transition: 0.3s; border: 1px solid transparent; }
        
        /* Warna biru jika kategori sedang dipilih */
        .category-item.active {
            background-color: #e3f2fd;
            border-color: #0056b3;
            transform: translateY(-3px);
        }
        
        /* CSS Kartu Produk (Biar Rapi) */
        .product-card {
            background: white; border: 1px solid #eee; border-radius: 8px; overflow: hidden;
            display: flex; flex-direction: column; transition: 0.3s;
        }
        .product-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); transform: translateY(-3px); }
        .card-img-top { width: 100%; height: 180px; object-fit: cover; background: #f9f9f9; }
        .card-body { padding: 15px; display: flex; flex-direction: column; flex: 1; }
        .p-cat { font-size: 11px; text-transform: uppercase; color: #888; margin-bottom: 5px; }
        .p-name { font-size: 16px; font-weight: bold; margin: 0 0 5px 0; color: #333; }
        .p-price { font-size: 18px; color: #0056b3; font-weight: bold; margin-top: auto; }
        .p-stock { font-size: 12px; color: #28a745; margin-bottom: 10px; }
        .btn-card { display: block; width: 100%; padding: 10px; background: #0056b3; color: white; text-align: center; border-radius: 5px; text-decoration: none; margin-top: 10px; border: none; cursor: pointer; }
        .btn-card:hover { background: #004494; }
        .empty-message { grid-column: 1/-1; text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span class="tagline">netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <div class="notif-wrapper">
                    <a class="notif-link" href="../notifikasi/notifikasi.php">🔔 Notifikasi</a>
                </div>
                
                <?php if ($isLoggedIn): ?>
                    <a href="../profil/profile.php">Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?> </a>
                    <span>|</span>
                    <a href="../logout.php">Logout</a>
                <?php else: ?>
                    <a href="../login/signup/signup.php">Daftar</a>
                    <span>|</span>
                    <a href="../login/login.php">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container header-content">
            <div class="logo">netofffice</div>
            
            <form action="" method="GET" class="search-container">
                <!-- Input hidden agar saat cari, kategori tetap terpilih (opsional) -->
                <?php if($selectedCategory): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <?php endif; ?>
                
                <input type="text" name="keyword" id="searchInput" placeholder="Cari di netofffice..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="search-btn">🔍</button>
            </form>
            
            <div class="header-actions">
                <a href="../pesanan/pesanan.php" class="orders-link">📦 Pesanan</a>
                <a href="../profil/profile.php" class="profile-link">👤 Profil</a>
                <a href="../keranjang/keranjang.php" class="cart-icon">🛒 <span id="cartCount" class="badge">0</span></a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            
            <!-- Banner Carousel -->
            <section class="banner-section">
                <div class="banner-carousel" id="bannerCarousel">
                    <div class="banner-slide active">
                        <div class="banner-content">
                            <h2>💼 Solusi Elektronik Kantor</h2>
                            <p>Diskon corporate untuk laptop & printer</p>
                            <!-- Tombol banner sekarang pakai Link PHP -->
                            <a href="?category=Laptop" class="btn-banner" style="text-decoration:none; display:inline-block;">Belanja Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="banner-dots" id="bannerDots"></div>
            </section>

            <!-- KATEGORI ELEKTRONIK (FITUR FILTER PHP) -->
            <section class="quick-categories">
                <h2 class="section-title">Kategori Elektronik</h2>
                <div class="category-grid">
                    
                    <!-- Link Reset -->
                    <a href="beranda.php" class="category-link">
                        <div class="category-item <?= empty($selectedCategory) ? 'active' : '' ?>">
                            <div class="category-icon">🏠</div>
                            <span>Semua</span>
                        </div>
                    </a>

                    <!-- Link Kategori Laptop -->
                    <a href="?category=Laptop" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'laptop' ? 'active' : '' ?>">
                            <div class="category-icon">💻</div>
                            <span>Laptop</span>
                        </div>
                    </a>

                    <a href="?category=Printer" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'printer' ? 'active' : '' ?>">
                            <div class="category-icon">🖨️</div>
                            <span>Printer</span>
                        </div>
                    </a>

                    <a href="?category=Smartphone" class="category-link">
                        <div class="category-item <?= (strtolower($selectedCategory) == 'smartphone' || strtolower($selectedCategory) == 'mobile') ? 'active' : '' ?>">
                            <div class="category-icon">📱</div>
                            <span>Handphone</span>
                        </div>
                    </a>

                    <a href="?category=Monitor" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'monitor' ? 'active' : '' ?>">
                            <div class="category-icon">🖥️</div>
                            <span>Monitor</span>
                        </div>
                    </a>

                    <a href="?category=Keyboard" class="category-link">
                        <div class="category-item <?= (strtolower($selectedCategory) == 'keyboard' || strtolower($selectedCategory) == 'peripheral') ? 'active' : '' ?>">
                            <div class="category-icon">⌨️</div>
                            <span>Aksesoris</span>
                        </div>
                    </a>

                    <a href="?category=Jaringan" class="category-link">
                        <div class="category-item <?= (strtolower($selectedCategory) == 'jaringan' || strtolower($selectedCategory) == 'network') ? 'active' : '' ?>">
                            <div class="category-icon">📡</div>
                            <span>Jaringan</span>
                        </div>
                    </a>

                </div>
            </section>

            <!-- REKOMENDASI PRODUK (Sekarang ada isinya) -->
            <!-- Disembunyikan kalau lagi filter kategori biar fokus -->
            <?php if(empty($selectedCategory) && empty($keyword)): ?>
            <section class="flash-sale-section">
                <div class="section-header">
                    <h2 class="section-title">✨ Rekomendasi Produk</h2>
                </div>
                <div class="product-grid" id="flashSaleProducts">
                    <?php if (mysqli_num_rows($queryRekomendasi) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($queryRekomendasi)): ?>
                            <?php 
                                $img = (!empty($row['image']) && file_exists("../uploads/".$row['image'])) ? "../uploads/".$row['image'] : "https://placehold.co/400x300?text=".urlencode($row['name']);
                            ?>
                            <div class="product-card">
                                <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                                <div class="card-body">
                                    <span class="p-cat"><?= htmlspecialchars($row['category_name']) ?></span>
                                    <h3 class="p-name"><?= htmlspecialchars($row['name']) ?></h3>
                                    <div class="p-price">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
                                    <div class="p-stock">Stok: <?= $row['stock'] ?></div>
                                    <a href="../detail/detail.php?id=<?= $row['product_id'] ?>" class="btn-card">Lihat Detail</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- PRODUK UTAMA (Hasil Filter Muncul Disini) -->
            <section class="products-section">
                <h2 class="section-title" id="productTitle">
                    <?php 
                        if ($selectedCategory) echo "📂 Kategori: " . htmlspecialchars($selectedCategory);
                        elseif ($keyword) echo "🔍 Hasil Pencarian: " . htmlspecialchars($keyword);
                        else echo "📦 Semua Produk Elektronik";
                    ?>
                </h2>
                
                <div class="product-grid" id="productList">
                    <?php if (mysqli_num_rows($queryProduk) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($queryProduk)): ?>
                            <?php 
                                $img = (!empty($row['image']) && file_exists("../uploads/".$row['image'])) ? "../uploads/".$row['image'] : "https://placehold.co/400x300?text=".urlencode($row['name']);
                            ?>
                            <div class="product-card">
                                <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                                <div class="card-body">
                                    <span class="p-cat"><?= htmlspecialchars($row['category_name']) ?></span>
                                    <h3 class="p-name"><?= htmlspecialchars($row['name']) ?></h3>
                                    <p style="font-size:12px; color:#666; margin-bottom:10px;"><?= substr($row['specifications'], 0, 40) ?>...</p>
                                    <div class="p-price">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
                                    <div class="p-stock">Stok: <?= $row['stock'] ?></div>
                                    <div style="margin-top:auto;">
                                        <button class="btn-card" onclick="addToCart(<?= $row['product_id'] ?>)" style="background:#28a745; margin-bottom:5px;">+ Keranjang</button>
                                        <a href="../detail/detail.php?id=<?= $row['product_id'] ?>" class="btn-card" style="background:#0056b3;">Detail</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="empty-message">Produk tidak ditemukan. <a href="beranda.php" style="color:#0056b3;">Reset Filter</a></p>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer & Scripts (Sama seperti template Anda) -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-section"><h3>netofffice</h3><p>Marketplace B2B Elektronik.</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2025 netofffice.</p></div>
    </footer>

    <script src="beranda.js"></script>
    <script>
        function addToCart(id) {
            alert("Produk ID " + id + " ditambahkan ke keranjang.");
            let badge = document.getElementById('cartCount');
            badge.innerText = parseInt(badge.innerText) + 1;
        }
    </script>
</body>
</html>