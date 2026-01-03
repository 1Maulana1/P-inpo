<?php
session_start();

// --- 1. KONEKSI DATABASE YANG LEBIH ROBUST ---
$koneksi = null;

// Cek beberapa kemungkinan lokasi file koneksi
if (file_exists('../koneksi.php')) {
    include '../koneksi.php';
} elseif (file_exists('test.php')) {
    include 'test.php';
} else {
    die("Error Fatal: File koneksi database tidak ditemukan. Pastikan path '../koneksi.php' benar.");
}

// PERBAIKAN UTAMA: Cek apakah variabel koneksi berhasil dibuat
// Jika $koneksi masih null, coba cek nama variabel lain yang mungkin dipakai user ($conn atau $mysqli)
if (!isset($koneksi) || !$koneksi) {
    if (isset($conn) && $conn) {
        $koneksi = $conn;
    } elseif (isset($mysqli) && $mysqli) {
        $koneksi = $mysqli;
    } else {
        die("Error Fatal: Gagal terhubung ke database. <br>
             Penyebab: Variabel <code>\$koneksi</code> kosong atau gagal login. <br>
             Solusi: Cek file <b>koneksi.php</b> Anda. Pastikan nama variabelnya <b>\$koneksi</b> dan username/password database benar.");
    }
}

// --- 2. CEK LOGIN ---
// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$pesan = "";
$error = "";

// --- 3. PROSES INPUT DATA (SAAT TOMBOL DISUBMIT) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $brand = mysqli_real_escape_string($koneksi, $_POST['brand']);
    $category_id = intval($_POST['category_id']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $specifications = mysqli_real_escape_string($koneksi, $_POST['specifications']);
    

    // Validasi sederhana
    if (empty($name) || empty($price) || $category_id == 0) {
        $error = "Nama, Kategori, dan Harga wajib diisi!";
    } else {
        // Query Insert
        $queryInsert = "INSERT INTO products (category_id, name, brand, specifications, price, stock) 
                        VALUES ('$category_id', '$name', '$brand', '$specifications', '$price', '$stock')";
        
        if (mysqli_query($koneksi, $queryInsert)) {
            $pesan = "✅ Produk berhasil ditambahkan!";
        } else {
            $error = "❌ Gagal menyimpan: " . mysqli_error($koneksi);
        }
    }
}

// --- 4. AMBIL DATA KATEGORI (UNTUK DROPDOWN) ---
$queryCat = "SELECT * FROM categories ORDER BY name ASC";
$resultCat = mysqli_query($koneksi, $queryCat);

// Cek error query kategori
if (!$resultCat) {
    die("Error Query Kategori: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - netofffice</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>

    <a href="index.php" class="btn-back">← Kembali ke Dashboard</a>

    <div class="container">
        <h2>Tambah Produk Baru</h2>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" placeholder="Contoh: Laptop Thinkpad X1" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while($cat = mysqli_fetch_assoc($resultCat)): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= $cat['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Merk / Brand</label>
                <input type="text" name="brand" placeholder="Contoh: Lenovo, HP, Epson">
            </div>

            <div class="form-row" style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" placeholder="0" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Stok</label>
                    <input type="number" name="stock" placeholder="Jumlah stok" required>
                </div>
            </div>

            <div class="form-group">
                <label>Spesifikasi / Deskripsi</label>
                <textarea name="specifications" rows="4" placeholder="Tulis spesifikasi lengkap di sini..."></textarea>
            </div>

            <div>
                <label for="image">Gambar Produk</label>
                <input type="file" name="image" id="image">
            </div>

            <button type="submit" class="btn-submit">Simpan Produk</button>
        </form>
    </div>

</body>
</html>