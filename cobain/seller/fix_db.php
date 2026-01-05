<?php
// File ini hanya dijalankan SEKALI untuk memperbaiki database
$mysqli = null;
if (file_exists('../test.php')) { include_once('../test.php'); } 
elseif (file_exists('../koneksi.php')) { include_once('../koneksi.php'); if(isset($koneksi))$mysqli=$koneksi; } 
else { $mysqli = mysqli_connect("localhost", "root", "", "netofffice_db"); }

if (!$mysqli) { die("Koneksi gagal: " . mysqli_connect_error()); }

echo "<h3>🔍 Memulai Perbaikan Database...</h3>";

// 1. Cek Kolom shop_id di tabel products
$check = mysqli_query($mysqli, "SHOW COLUMNS FROM products LIKE 'shop_id'");
if (mysqli_num_rows($check) == 0) {
    // Jika tidak ada, buat kolomnya
    $sql = "ALTER TABLE products ADD COLUMN shop_id INT DEFAULT NULL AFTER category_id";
    if (mysqli_query($mysqli, $sql)) {
        echo "<p style='color:green'>✅ Berhasil menambahkan kolom 'shop_id' ke tabel 'products'.</p>";
        
        // Tambahkan Index biar cepat
        mysqli_query($mysqli, "ALTER TABLE products ADD INDEX (shop_id)");
    } else {
        echo "<p style='color:red'>❌ Gagal update tabel: " . mysqli_error($mysqli) . "</p>";
    }
} else {
    echo "<p style='color:blue'>ℹ️ Kolom 'shop_id' sudah ada di tabel 'products'.</p>";
}

// 2. Cek apakah Tabel Shops ada
$checkShops = mysqli_query($mysqli, "SHOW TABLES LIKE 'shops'");
if (mysqli_num_rows($checkShops) == 0) {
    echo "<p style='color:red'>❌ Tabel 'shops' belum ada! Jalankan script SQL pendaftaran toko.</p>";
} else {
    echo "<p style='color:green'>✅ Tabel 'shops' sudah siap.</p>";
}

echo "<hr><a href='dashboard_seller.php'>Kembali ke Dashboard Seller</a>";
?>