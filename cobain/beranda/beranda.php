<?php
session_start();

// --- KONEKSI DATABASE ---
include_once("../login/test.php");

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



// B. Query Produk (Dengan Filter)
$queryProduk = mysqli_query($mysqli, "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    $sqlWhere
    ORDER BY p.created_at DESC
");
?>
<?php
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

    
            $totalQty += $qty;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>netoffice - Beranda</title>
    <link rel="stylesheet" href="beranda.css">
    
    <style>

    </style>
</head>

<body>

    <!-- Container Notifikasi (Wajib Ada) -->
    <div id="toast-container"></div>

    <!-- Top Bar & Header -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span class="tagline">netoffice · B2B Elektronik Kantor</span>
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
            <div class="logo">netoffice</div>
            
            <form action="" method="GET" class="search-container">
                <!-- Input hidden agar saat cari, kategori tetap terpilih (opsional) -->
                <?php if($selectedCategory): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <?php endif; ?>
                
                <input type="text" name="keyword" id="searchInput" placeholder="Cari di netoffice..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="search-btn">🔍</button>
            </form>
            
            <div class="header-actions">
                <a href="../pesanan/pesanan.php" class="orders-link">📦 Pesanan</a>
                <a href="../profil/profile.php" class="profile-link">👤 Profil</a>
                <a href="../keranjang/keranjang.php" class="cart-icon">🛒 <span id="cartCount" class="badge"><?= $totalQty ?></span></a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            
            <!-- Banner Carousel -->
            <section class="banner-section">
                <div class="banner-carousel">
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

            <!-- KATEGORI ELEKTRONIK -->
            <section class="quick-categories">
                <h2 class="section-title">Kategori Elektronik</h2>
                <div class="category-grid">
                    <a href="beranda.php" class="category-link">
                        <div class="category-item <?= empty($selectedCategory) ? 'active' : '' ?>">
                            <div class="category-icon">🏠</div><span>Semua</span>
                        </div>
                    </a>
                    <a href="?category=Laptop" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'laptop' ? 'active' : '' ?>">
                            <div class="category-icon">💻</div><span>Laptop & PC</span>
                        </div>
                    </a>
                    <a href="?category=Office" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'office' ? 'active' : '' ?>">
                            <div class="category-icon">🖨️</div><span>Office</span>
                        </div>
                    </a>
                    <a href="?category=Smartphone" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'smartphone' ? 'active' : '' ?>">
                            <div class="category-icon">📱</div><span>Handphone</span>
                        </div>
                    </a>
                    <a href="?category=Peripheral" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'peripheral' ? 'active' : '' ?>">
                            <div class="category-icon">⌨️</div><span>Peripheral</span>
                        </div>
                    </a>
                    <a href="?category=Jaringan" class="category-link">
                        <div class="category-item <?= strtolower($selectedCategory) == 'jaringan' ? 'active' : '' ?>">
                            <div class="category-icon">📡</div><span>Jaringan</span>
                        </div>
                    </a>
                </div>
            </section>

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
                            <div class="product-card"> <a href="detail.php?id=<?= $row['product_id'] ?>">
                                <img src="<?= $img ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                                <div class="card-body">
                                    <span class="p-cat"><?= htmlspecialchars($row['category_name']) ?></span>
                                    <h3 class="p-name"><?= htmlspecialchars($row['name']) ?></h3>
                                    <p style="font-size:12px; color:#666; margin-bottom:10px;"><?= substr($row['specifications'], 0, 40) ?>...</p>
                                    <div class="p-price">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
                                    <div class="p-stock">Stok: <?= $row['stock'] ?></div>
                                    </a>
                                    <div style="margin-top:auto;">
                                        <!-- Tombol Pemicu Toast -->
                                        <button class="btn-card" onclick="addToCart(<?= $row['product_id'] ?>, '<?= addslashes($row['name']) ?>')" style="background:#28a745; margin-bottom:5px;">+ Keranjang</button>
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

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-section"><h3>netoffice</h3><p>Marketplace B2B Elektronik.</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2025 netoffice.</p></div>
    </footer>

    <script src="beranda.js" defer></script>
    
    <!-- JAVASCRIPT NOTIFIKASI & KERANJANG -->
</body>
</html>