<?php
session_start();

// --- 1. KONEKSI DATABASE ---
$mysqli = null;
if (file_exists('../test.php')) { include_once('../test.php'); } 
elseif (file_exists('../koneksi.php')) { include_once('../koneksi.php'); } 
elseif (file_exists('test.php')) { include_once('test.php'); }

if (isset($koneksi) && $koneksi) { $mysqli = $koneksi; } 
elseif (isset($conn) && $conn) { $mysqli = $conn; }

if (!$mysqli) { $mysqli = @mysqli_connect("localhost", "root", "", "netofffice_db"); }
if (!$mysqli) { die("<h3>Koneksi Gagal</h3><p>Tidak dapat terhubung ke database.</p>"); }

// --- 2. CEK LOGIN ---
if (!isset($_SESSION['user_id'])) {
    // Recovery session by nama
    if (isset($_SESSION['nama'])) {
        $nama = mysqli_real_escape_string($mysqli, $_SESSION['nama']);
        $q = mysqli_query($mysqli, "SELECT user_id FROM users WHERE full_name = '$nama' OR nama = '$nama' LIMIT 1");
        if ($r = mysqli_fetch_assoc($q)) {
            $_SESSION['user_id'] = $r['user_id'];
        } else {
            header("Location: ../login/login.php"); exit();
        }
    } else {
        header("Location: ../login/login.php"); exit();
    }
}

$user_id = $_SESSION['user_id'];

// --- 3. AMBIL DATA PESANAN ---
$query = "SELECT 
            o.order_id, o.order_date, o.total_amount, o.status,
            oi.quantity, oi.price_at_purchase,
            p.name AS product_name, p.image, p.brand
          FROM orders o
          JOIN order_items oi ON o.order_id = oi.order_id
          JOIN products p ON oi.product_id = p.product_id
          WHERE o.user_id = '$user_id'
          ORDER BY o.order_date DESC";

$result = mysqli_query($mysqli, $query);
if (!$result) { die("Error Database Query: " . mysqli_error($mysqli)); }

// --- 4. FORMAT DATA KE JSON ---
$ordersData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $oid = $row['order_id'];
    $statusCode = 'unpaid';
    $statusLabel = 'Belum Bayar';
    
    switch (strtolower($row['status'])) {
        case 'pending': $statusCode = 'unpaid'; $statusLabel = 'Belum Bayar'; break;
        case 'paid': $statusCode = 'shipping'; $statusLabel = 'Sedang Dikemas'; break;
        case 'shipped': $statusCode = 'shipping'; $statusLabel = 'Dikirim'; break;
        case 'completed': case 'done': case 'selesai': $statusCode = 'completed'; $statusLabel = 'Selesai'; break;
        case 'cancelled': $statusCode = 'cancelled'; $statusLabel = 'Dibatalkan'; break;
    }

    if (!isset($ordersData[$oid])) {
        $ordersData[$oid] = [
            'id' => 'ORD-' . $oid,
            'real_id' => $oid,
            'shopName' => 'NetOffice Official',
            'status' => $row['status'],
            'statusCode' => $statusCode,
            'statusLabel' => $statusLabel,
            'total' => (int)$row['total_amount'],
            'items' => []
        ];
    }

    $imgSource = "../uploads/" . $row['image'];
    $displayImg = (!empty($row['image']) && file_exists("../" . $imgSource)) 
        ? $imgSource : "https://placehold.co/80x80/eee/999?text=" . urlencode(substr($row['product_name'], 0, 3));

    $ordersData[$oid]['items'][] = [
        'name' => $row['product_name'],
        'variant' => $row['brand'],
        'quantity' => (int)$row['quantity'],
        'price' => (int)$row['price_at_purchase'],
        'image' => $displayImg
    ];
}
$ordersJSON = json_encode(array_values($ordersData));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - netofffice</title>
    <!-- Panggil File CSS Terpisah -->
    <link rel="stylesheet" href="pesananp.css">
</head>
<body>

    <div class="container">
        <div class="page-header">
            <a href="../beranda/beranda.php" class="btn-back">←</a>
            <h1>Pesanan Saya</h1>
        </div>

        <div class="tabs-container" id="tabs-container"></div>
        <div id="orders-list"></div>
    </div>

    <!-- Inject Data PHP ke Variable Global JS -->
    <script>
        window.ordersData = <?= $ordersJSON ?>;
    </script>

    <!-- Panggil File JS Terpisah -->
    <script src="pesanan_p.js"></script>

</body>
</html>