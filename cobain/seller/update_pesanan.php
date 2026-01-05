<?php
session_start();

// 1. KONEKSI
$mysqli = null;
if (file_exists('../test.php')) { include_once('../test.php'); } 
elseif (file_exists('../koneksi.php')) { include_once('../koneksi.php'); if(isset($koneksi))$mysqli=$koneksi; } 
else { $mysqli = mysqli_connect("localhost", "root", "", "netofffice_db"); }

// 2. CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak.");
}

// 3. AMBIL DATA
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Validasi Status yang diperbolehkan
$allowedStatus = ['Paid', 'Shipped', 'Cancelled', 'Completed'];

if ($order_id > 0 && in_array($status, $allowedStatus)) {
    
    // 4. UPDATE DATABASE
    // Hanya update jika order tersebut memang ada (bisa ditambah validasi shop_id jika ingin lebih ketat)
    $query = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";
    
    if (mysqli_query($mysqli, $query)) {
        // Jika sukses, kembali ke dashboard
        header("Location: dashboard_seller.php?msg=sukses");
    } else {
        echo "Gagal update: " . mysqli_error($mysqli);
    }

} else {
    echo "Data tidak valid.";
}
?>