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

// 3. CEK APAKAH SUDAH PUNYA TOKO?
$cekToko = mysqli_query($mysqli, "SELECT * FROM shops WHERE user_id = '$user_id'");
$dataToko = mysqli_fetch_assoc($cekToko);

// --- PERBAIKAN ANTI-LOOP DI SINI ---
if ($dataToko) {
    // JANGAN redirect otomatis header("Location: dashboard_seller.php");
    // Tampilkan pesan konfirmasi saja agar loop terputus
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Toko Sudah Ada</title>
        <style>
            body { font-family: sans-serif; text-align: center; padding: 50px; background: #f4f6f8; }
            .card { background: white; max-width: 400px; margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
            h2 { color: #0056b3; }
            .btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>Anda Sudah Punya Toko!</h2>
            <p>Nama Toko: <b><?= htmlspecialchars($dataToko['shop_name']) ?></b></p>
            <p>Anda tidak perlu mendaftar lagi.</p>
            <a href="dashboard_seller.php" class="btn">Masuk ke Dashboard Seller</a>
        </div>
    </body>
    </html>
    <?php
    exit(); // Stop script di sini
}

// 4. PROSES FORMULIR (Jika belum punya toko)
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_toko = mysqli_real_escape_string($mysqli, $_POST['shop_name']);
    $alamat_toko = mysqli_real_escape_string($mysqli, $_POST['shop_address']);
    $no_hp = mysqli_real_escape_string($mysqli, $_POST['phone_number']);

    if (!empty($nama_toko) && !empty($alamat_toko)) {
        $queryInsert = "INSERT INTO shops (user_id, shop_name, shop_address, phone_number) 
                        VALUES ('$user_id', '$nama_toko', '$alamat_toko', '$no_hp')";
        
        if (mysqli_query($mysqli, $queryInsert)) {
            $_SESSION['has_shop'] = true;
            echo "<script>alert('Toko berhasil dibuat!'); window.location='dashboard_seller.php';</script>";
            exit();
        } else {
            $error = "Gagal membuat toko: " . mysqli_error($mysqli);
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Toko - netofffice</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f8; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #0056b3; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #0056b3; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #004494; }
        .error { color: red; font-size: 14px; text-align: center; margin-bottom: 15px; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

    <div class="card">
        <h2>🏪 Mulai Berjualan</h2>
        <p style="text-align:center; color:#666; margin-bottom:20px;">Silakan lengkapi data toko Anda.</p>

        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Toko</label>
                <input type="text" name="shop_name" placeholder="Contoh: NetOffice Store" required>
            </div>
            <div class="form-group">
                <label>Nomor HP Toko</label>
                <input type="text" name="phone_number" placeholder="0812..." required>
            </div>
            <div class="form-group">
                <label>Alamat Toko</label>
                <textarea name="shop_address" rows="3" placeholder="Alamat lengkap pengiriman barang" required></textarea>
            </div>
            <button type="submit" class="btn">Buka Toko Gratis</button>
        </form>
        
        <a href="../beranda/beranda.php" class="back-link">Batal & Kembali</a>
    </div>

</body>
</html>