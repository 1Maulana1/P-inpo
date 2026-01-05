<?php
session_start();

// 1. KONEKSI DATABASE
include_once("../login/test.php");  
// 2. CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 3. CEK APAKAH USER SUDAH PUNYA TOKO? (LOGIKA BARU)
// Kita cari shop_id milik user ini
$queryShop = mysqli_query($mysqli, "SELECT shop_id FROM shops WHERE user_id = '$user_id'");
$shopData = mysqli_fetch_assoc($queryShop);

// Jika belum punya toko, paksa ke halaman buka toko
if (!$shopData) {
    echo "<script>alert('Anda harus membuka toko terlebih dahulu untuk mulai berjualan.'); window.location='buka_toko.php';</script>";
    exit();
}

$shop_id = $shopData['shop_id']; // Simpan ID Toko untuk query insert nanti

$pesan = "";
$error = "";

// 4. PROSES INPUT DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $brand = mysqli_real_escape_string($mysqli, $_POST['brand']);
    $category_id = intval($_POST['category_id']);
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock']);
    $specifications = mysqli_real_escape_string($mysqli, $_POST['specifications']);
    
    // Upload Gambar
    $imageName = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $newName = uniqid() . "." . $ext;
            // Pastikan folder uploads ada di root project
            $dest = "../uploads/" . $newName; 
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $dest)) {
                $imageName = $newName;
            }
        }
    }

    // Insert ke Database
    if (empty($name) || empty($price)) {
        $error = "Nama dan Harga wajib diisi!";
    } else {
        // UPDATE QUERY: Menambahkan shop_id ke dalam insert
        $queryInsert = "INSERT INTO products (shop_id, category_id, name, brand, specifications, price, stock, image) 
                        VALUES ('$shop_id', '$category_id', '$name', '$brand', '$specifications', '$price', '$stock', '$imageName')";
        
        if (mysqli_query($mysqli, $queryInsert)) {
            $pesan = "✅ Produk berhasil ditambahkan ke Toko Anda!";
        } else {
            $error = "Gagal menyimpan: " . mysqli_error($mysqli);
        }
    }
}

// Ambil Kategori
$catResult = mysqli_query($mysqli, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual Produk - netofffice</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #0056b3; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .btn:hover { background: #004494; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .back-link { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

    <a href="dashboard_seller.php" class="back-link">← Kembali ke Dashboard</a>

    <div class="container">
        <h2>Tambah Produk Baru</h2>

        <?php if($pesan) echo "<div class='alert-success'>$pesan</div>"; ?>
        <?php if($error) echo "<div class='alert-danger'>$error</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="gambar" accept="image/*">
            </div>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required placeholder="Contoh: Laptop ASUS ROG">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id">
                    <?php while($c = mysqli_fetch_assoc($catResult)): ?>
                        <option value="<?= $c['category_id'] ?>"><?= $c['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Merk / Brand</label>
                <input type="text" name="brand" placeholder="Contoh: ASUS">
            </div>

            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Stok</label>
                    <input type="number" name="stock" required>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="specifications" rows="4"></textarea>
            </div>

            <button type="submit" class="btn">Simpan Produk</button>
        </form>
    </div>

</body>
</html>