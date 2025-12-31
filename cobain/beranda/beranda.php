<?php
// beranda.php — ambil data products & stores dari MySQL
// Konfigurasi koneksi: gunakan environment variables bila tersedia
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'netofffice';

$productsJson = '[]';
$storesJson = '{}';

// Coba koneksi database secara aman; jika gagal, tetap tampilkan halaman statis
try {
    $mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($mysqli && !$mysqli->connect_errno) {
        $mysqli->set_charset('utf8mb4');

        // Ambil produk aktif
        $sqlProducts = "SELECT id, name, price, img, `desc`, store_id FROM products WHERE active = 1 ORDER BY created_at DESC LIMIT 500";
        $products = [];
        if ($res = $mysqli->query($sqlProducts)) {
            while ($row = $res->fetch_assoc()) {
                $products[] = [
                    'id' => (int)$row['id'],
                    'name' => $row['name'],
                    'price' => (float)$row['price'],
                    'img' => $row['img'],
                    'desc' => $row['desc'],
                    'storeId' => $row['store_id']
                ];
            }
            $res->free();
        }

        // Ambil daftar toko
        $sqlStores = "SELECT id, name, rating, active FROM stores";
        $stores = [];
        if ($res = $mysqli->query($sqlStores)) {
            while ($row = $res->fetch_assoc()) {
                $stores[$row['id']] = [
                    'name' => $row['name'],
                    'rating' => $row['rating'],
                    'active' => ($row['active'] == 1) ? 'Buka' : 'Tutup'
                ];
            }
            $res->free();
        }

        $mysqli->close();

        $productsJson = json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $storesJson = json_encode($stores, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
} catch (Exception $e) {
    // ignore and fall back to empty datasets
}

// Siapkan skrip injeksi data ke JS
$injectScriptTag = "<script>var products = $productsJson; var stores = $storesJson;</script>\n";

// Muat template HTML dan sisipkan variabel JS menggantikan ../toko/data.js jika ada,
// atau injeksikan sebelum </body> sebagai cadangan saat menampilkan.
$templatePath = __DIR__ . '/beranda.html';
if (file_exists($templatePath)) {
    $template = file_get_contents($templatePath);
    $pattern = '/<script\s+src=[\'\"]\.\.\/toko\/data\.js[\'\"]\s*><\/script>/i';
    if (preg_match($pattern, $template)) {
        $template = preg_replace($pattern, $injectScriptTag, $template, 1);
    } else {
        $template = preg_replace('/<\/body>/i', $injectScriptTag . "</body>", $template, 1);
    }
    // Kirim output template yang sudah disuntik data
    header('Content-Type: text/html; charset=UTF-8');
    echo $template;
    return;
}

// Jika template tidak ditemukan, tampilkan minimal fallback
header('Content-Type: text/html; charset=UTF-8');
echo "<!doctype html><html><body><h1>Template beranda.html tidak ditemukan.</h1></body></html>";
return;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>netofffice - Beranda</title>
    <link rel="stylesheet" href="beranda.css">
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
                    <a class="notif-link" href="../notifikasi/notifikasi.html" aria-label="Notifikasi">🔔 Notifikasi</a>
                    <div class="notif-pop">
                        <p class="notif-title">Pemberitahuan</p>
                        <ul>
                            <li>Invoice baru siap diunduh.</li>
                            <li>Stok printer laser hampir habis.</li>
                            <li>Penawaran khusus untuk pembelian 10+ unit.</li>
                        </ul>
                    </div>
                </div>
                <a href="../login/signup/signup.html">Daftar</a>
                <span>|</span>
                <a href="../login/login.html">Log In</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container header-content">
            <div class="logo">netofffice</div>
            
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Cari elektronik kantor di netofffice" oninput="searchProduct()">
                <button type="button" class="search-btn" onclick="searchProduct()">🔍</button>
            </div>
            
            <div class="header-actions">
                <a href="../pesanan/pesanan.html" class="orders-link" title="Pesanan Saya">
                    📦 Pesanan
                </a>
                <a href="../profil/Profile.html" class="profile-link" title="Profil">
                    👤 Profil
                </a>
                <a href="../keranjang/keranjang.html" class="cart-icon" title="Keranjang">
                    🛒 <span id="cartCount" class="badge">0</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            
            <!-- Banner Carousel -->
            <section class="banner-section">
                <div class="banner-carousel" id="bannerCarousel">
                    <div class="banner-slide active" data-category="laptop" onclick="scrollToProducts('laptop')">
                        <div class="banner-content">
                            <h2>💼 Solusi Elektronik Kantor</h2>
                            <p>Diskon corporate untuk laptop & printer</p>
                            <button class="btn-banner">Belanja Sekarang</button>
                        </div>
                    </div>
                    <div class="banner-slide" data-category="printer" onclick="scrollToProducts('printer')">
                        <div class="banner-content">
                            <h2>🖨️ Cetak Tanpa Henti</h2>
                            <p>Paket tinta & maintenance B2B</p>
                            <button class="btn-banner">Lihat Printer</button>
                        </div>
                    </div>
                    <div class="banner-slide" data-category="network" onclick="scrollToProducts('network')">
                        <div class="banner-content">
                            <h2>🌐 Koneksi Andal</h2>
                            <p>Router, switch, dan aksesori jaringan</p>
                            <button class="btn-banner">Cek Jaringan</button>
                        </div>
                    </div>
                </div>
                <div class="banner-dots" id="bannerDots"></div>
            </section>

            <!-- Kategori Elektronik Kantor -->
            <section class="quick-categories">
                <h2 class="section-title">Kategori Elektronik</h2>
                <div class="category-grid">
                    <div class="category-item" onclick="filterByCategory('laptop')">
                        <div class="category-icon">💻</div>
                        <span>Laptop & Ultrabook</span>
                    </div>
                    <div class="category-item" onclick="filterByCategory('printer')">
                        <div class="category-icon">🖨️</div>
                        <span>Printer & Tinta</span>
                    </div>
                    <div class="category-item" onclick="filterByCategory('mobile')">
                        <div class="category-icon">📱</div>
                        <span>Handphone Kerja</span>
                    </div>
                    <div class="category-item" onclick="filterByCategory('monitor')">
                        <div class="category-icon">🖥️</div>
                        <span>Monitor & Display</span>
                    </div>
                    <div class="category-item" onclick="filterByCategory('peripheral')">
                        <div class="category-icon">⌨️</div>
                        <span>Keyboard & Mouse</span>
                    </div>
                    <div class="category-item" onclick="filterByCategory('network')">
                        <div class="category-icon">📡</div>
                        <span>Jaringan & Router</span>
                    </div>
                </div>
            </section>

            <!-- Rekomendasi Produk -->
            <section class="flash-sale-section">
                <div class="section-header">
                    <h2 class="section-title">✨ Rekomendasi Produk</h2>
                </div>
                <div class="product-grid" id="flashSaleProducts"></div>
            </section>

            <!-- Produk Terbaru -->
            <section class="products-section">
                <h2 class="section-title" id="productTitle">📦 Produk Elektronik Kantor</h2>
                <div class="product-grid" id="productList"></div>
            </section>

        </div>
    </main>

    <!-- Modal Detail Produk -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeProductModal()">&times;</span>
            <div class="modal-body">
                <img id="modalProductImg" src="" alt="Product Image">
                <div class="modal-info">
                    <h2 id="modalProductName"></h2>
                    <p class="modal-store" onclick="goToStore()">🏪 <span id="modalStoreName"></span></p>
                    <p class="modal-price" id="modalProductPrice"></p>
                    <p class="modal-desc" id="modalProductDesc"></p>
                    <div class="modal-actions">
                        <button class="btn-add-cart" onclick="addToCart()">🛒 Masukkan Keranjang</button>
                        <button class="btn-buy-now" onclick="buyNow()">💳 Beli Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-section">
                <h3>netofffice</h3>
                <p>Marketplace B2B untuk elektronik kantor: printer, laptop, jaringan, dan aksesoris kerja.</p>
            </div>
            <div class="footer-section">
                <h4>Layanan</h4>
                <a href="#">Bantuan</a>
                <a href="#">Cara Belanja</a>
                <a href="#">Pembayaran</a>
                <a href="#">Pengiriman</a>
            </div>
            <div class="footer-section">
                <h4>Tentang Kami</h4>
                <a href="#">Tentang netofffice</a>
                <a href="#">Karir</a>
                <a href="#">Kebijakan</a>
            </div>
            <div class="footer-section">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="#">📘 Facebook</a>
                    <a href="#">📸 Instagram</a>
                    <a href="#">🐦 Twitter</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 netofffice. All rights reserved.</p>
        </div>
    </footer>

    <!-- Chat Button -->
    <div class="chat-button" title="Hubungi CS">💬</div>

    <script src="../toko/data.js"></script>
    <script src="beranda.js"></script>
</body>
</html>
