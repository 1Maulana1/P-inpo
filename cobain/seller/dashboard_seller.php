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

// 3. CEK DATA TOKO
// Pastikan user sudah punya toko
$queryShop = mysqli_query($mysqli, "SELECT * FROM shops WHERE user_id = '$user_id'");
$shop = mysqli_fetch_assoc($queryShop);

if (!$shop) {
    // Kalau belum punya toko, lempar ke halaman buka toko
    header("Location: buka_toko.php");
    exit();
}

$shop_id = $shop['shop_id'];

// --- LOGIKA DATA DASHBOARD ---

// A. Hitung Total Produk
$qProd = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM products WHERE shop_id = '$shop_id'");
$totalProduk = mysqli_fetch_assoc($qProd)['total'];

// B. Ambil Daftar Produk Saya
$queryProducts = mysqli_query($mysqli, "SELECT * FROM products WHERE shop_id = '$shop_id' ORDER BY created_at DESC");

// C. Ambil Pesanan Masuk (Complex Query)
// PERBAIKAN DI SINI: Mengganti u.full_name menjadi u.nama
$queryOrders = "SELECT 
                    o.order_id, o.order_date, o.status, o.shipping_address,
                    oi.quantity, oi.price_at_purchase,
                    p.name as product_name, p.image,
                    u.nama as buyer_name
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                JOIN orders o ON oi.order_id = o.order_id
                JOIN users u ON o.user_id = u.user_id
                WHERE p.shop_id = '$shop_id'
                ORDER BY o.order_date DESC";

$resultOrders = mysqli_query($mysqli, $queryOrders);


// Cek Error Query jika kolom nama salah lagi
if (!$resultOrders) {
    // Fallback: Coba query ulang pakai full_name jika nama gagal, atau tampilkan error
    die("Error mengambil pesanan: " . mysqli_error($mysqli) . "<br>Pastikan tabel 'users' punya kolom 'nama' atau 'full_name'.");
}

// Hitung Total Pendapatan (Sederhana)
$qIncome = mysqli_query($mysqli, "
    SELECT SUM(oi.quantity * oi.price_at_purchase) as income 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.product_id 
    WHERE p.shop_id = '$shop_id'
");
$totalIncome = mysqli_fetch_assoc($qIncome)['income'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Seller - <?= htmlspecialchars($shop['shop_name']) ?></title>
    <style>
        /* CSS RESET & VARS */
        :root { --primary: #0056b3; --bg: #f4f6f8; --white: #ffffff; --text: #333; --border: #e0e0e0; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; background: var(--white); border-right: 1px solid var(--border); position: fixed; height: 100%; top: 0; left: 0; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid var(--border); }
        .sidebar-header h2 { margin: 0; color: var(--primary); font-size: 20px; }
        .sidebar-header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        
        .nav-links { padding: 20px 0; flex: 1; }
        .nav-item { display: block; padding: 12px 20px; color: #555; text-decoration: none; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
        .nav-item:hover, .nav-item.active { background: #f0f7ff; color: var(--primary); border-left-color: var(--primary); }
        .nav-item-logout { color: #dc3545; margin-top: auto; border-top: 1px solid var(--border); }
        .nav-item-logout:hover { background: #fff5f5; color: #c82333; border-left-color: #c82333; }

        /* MAIN CONTENT */
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .header-content { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-add { background: var(--primary); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-add:hover { background: #004494; }

        /* STATS CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .card h3 { margin: 0; color: #666; font-size: 14px; font-weight: normal; }
        .card .number { font-size: 24px; font-weight: bold; color: var(--text); margin-top: 5px; }
        .card.blue { border-top: 4px solid var(--primary); }
        .card.green { border-top: 4px solid #28a745; }
        .card.orange { border-top: 4px solid #fd7e14; }

        /* TABLES */
        .table-container { background: white; border-radius: 8px; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow-x: auto; }
        .section-title { font-size: 18px; margin-bottom: 15px; font-weight: bold; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { color: #666; font-weight: 600; background: #f9f9f9; }
        tr:last-child td { border-bottom: none; }
        
        .product-img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; background: #eee; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        
        .action-btn { font-size: 12px; padding: 5px 10px; border-radius: 3px; text-decoration: none; margin-right: 5px; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Seller Center</h2>
            <p><?= htmlspecialchars($shop['shop_name']) ?></p>
        </div>
        <div class="nav-links">
            <a href="#" class="nav-item active">Dashboard</a>
            <a href="tambah_produk.php" class="nav-item">Tambah Produk</a>
            <a href="#" class="nav-item">Pesanan Masuk</a>
            
            <a href="../beranda/beranda.php" class="nav-item" style="border-top:1px solid #eee; margin-top:20px;">Kembali ke Beranda</a>
            <a href="../logout.php" class="nav-item nav-item-logout">Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="main-content">
        <div class="header-content">
            <h1>Ringkasan Toko</h1>
            <a href="tambah_produk.php" class="btn-add">+ Tambah Produk Baru</a>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="card blue">
                <h3>Total Produk</h3>
                <div class="number"><?= $totalProduk ?></div>
            </div>
            <div class="card green">
                <h3>Total Pendapatan</h3>
                <div class="number">Rp <?= number_format($totalIncome, 0, ',', '.') ?></div>
            </div>
            <div class="card orange">
                <h3>Pesanan Masuk</h3>
                <div class="number"><?= mysqli_num_rows($resultOrders) ?></div>
            </div>
        </div>

        <!-- TABEL PESANAN MASUK -->
        <div class="table-container">
            <div class="section-title">📦 Pesanan Terbaru</div>
            
            <?php if (mysqli_num_rows($resultOrders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($resultOrders)): 
                        $statusClass = 'status-pending';
                        if(strtolower($order['status']) == 'paid') $statusClass = 'status-paid';
                        if(strtolower($order['status']) == 'shipped') $statusClass = 'status-shipped';
                    ?>
                    <tr>
                        <td>#<?= $order['order_id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                        <td style="display:flex; align-items:center; gap:10px;">
                            <?php 
                                $img = (!empty($order['image']) && file_exists("../uploads/".$order['image'])) 
                                    ? "../uploads/".$order['image'] 
                                    : "https://placehold.co/40x40?text=P";
                            ?>
                            <img src="<?= $img ?>" class="product-img">
                            <div>
                                <?= htmlspecialchars($order['product_name']) ?> <br>
                                <small>x<?= $order['quantity'] ?></small>
                            </div>
                        </td>
                        <td>
                            <!-- Perbaikan: Menampilkan Nama dari kolom 'u.nama' -->
                            <b><?= htmlspecialchars($order['buyer_name']) ?></b><br>
                            <small><?= substr(htmlspecialchars($order['shipping_address']), 0, 20) ?>...</small>
                        </td>
                        <td>Rp <?= number_format($order['quantity'] * $order['price_at_purchase'], 0, ',', '.') ?></td>
                        <td><span class="status-badge <?= $statusClass ?>"><?= $order['status'] ?></span></td>
                        <td>
                            <?php if($order['status'] == 'Pending' || $order['status'] == 'Paid'): ?>
                                <a href="#" onclick="alert('Fitur Kirim Barang akan segera hadir!')" class="action-btn" style="background:#007bff; color:white;">Proses</a>
                            <?php else: ?>
                                <span style="color:#aaa;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align:center; color:#888;">Belum ada pesanan masuk.</p>
            <?php endif; ?>
        </div>

        <!-- TABEL PRODUK SAYA -->
        <div class="table-container">
            <div class="section-title">📂 Produk Saya</div>
            
            <?php if (mysqli_num_rows($queryProducts) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="50">Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($prod = mysqli_fetch_assoc($queryProducts)): ?>
                    <tr>
                        <td>
                            <?php 
                                $img = (!empty($prod['image']) && file_exists("../uploads/".$prod['image'])) 
                                    ? "../uploads/".$prod['image'] 
                                    : "https://placehold.co/40x40?text=P";
                            ?>
                            <img src="<?= $img ?>" class="product-img">
                        </td>
                        <td>
                            <?= htmlspecialchars($prod['name']) ?><br>
                            <small style="color:#888;"><?= htmlspecialchars($prod['brand']) ?></small>
                        </td>
                        <td>Rp <?= number_format($prod['price'], 0, ',', '.') ?></td>
                        <td><?= $prod['stock'] ?></td>
                        <td>
                            <a href="#" class="action-btn btn-edit">Edit</a>
                            <a href="#" onclick="return confirm('Hapus produk ini?')" class="action-btn btn-delete">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align:center; color:#888;">Anda belum menambahkan produk.</p>
                <center><a href="tambah_produk.php" class="btn-add" style="font-size:12px;">+ Tambah Sekarang</a></center>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>