<?php
session_start();

// 1. PENTING: MATIKAN ERROR REPORTING AGAR JSON BERSIH
error_reporting(0);
ini_set('display_errors', 0);

// Beritahu browser bahwa ini adalah respon JSON
header('Content-Type: application/json; charset=utf-8');

// Fungsi Helper untuk kirim respon JSON dan berhenti
function sendResponse($status, $message, $extra = []) {
    echo json_encode(array_merge(["status" => $status, "message" => $message], $extra));
    exit();
}

// 2. KONEKSI DATABASE (Lebih Aman)
include_once("test.php");

// 3. VALIDASI USER & KERANJANG
if (!isset($_SESSION['user_id'])) {
    sendResponse("error", "Sesi login habis. Silakan login ulang.");
}

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    sendResponse("error", "Keranjang belanja kosong.");
}

// 4. PROSES DATA POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $nama = mysqli_real_escape_string($mysqli, $_POST['nama_penerima'] ?? '');
    $telp = mysqli_real_escape_string($mysqli, $_POST['telepon'] ?? '');
    $alamat = mysqli_real_escape_string($mysqli, $_POST['alamat'] ?? '');
    $metode = mysqli_real_escape_string($mysqli, $_POST['metode_pembayaran'] ?? '');
    $total = $_POST['total_hidden'] ?? 0;
    $tanggal = date('Y-m-d H:i:s');

    // Validasi input
    if (empty($nama) || empty($alamat) || empty($metode)) {
        sendResponse("error", "Mohon lengkapi semua data.");
    }

    // 5. INSERT KE TABEL ORDERS
    $full_address = "Penerima: $nama ($telp) - Alamat: $alamat";
    
    $queryOrder = "INSERT INTO orders (user_id, order_date, total_amount, status, shipping_address, payment_method) 
                   VALUES ('$user_id', '$tanggal', '$total', 'Pending', '$full_address', '$metode')";
    
    if (mysqli_query($mysqli, $queryOrder)) {
        // Ambil ID Order yang baru dibuat
        $order_id = mysqli_insert_id($mysqli);

        // 6. INSERT KE TABEL ORDER_ITEMS
        $ids = array_keys($_SESSION['keranjang']);
        // Pastikan ID aman (hanya angka)
        $clean_ids = array_map('intval', $ids);
        $ids_string = implode(',', $clean_ids);

        if (!empty($ids_string)) {
            $queryProduk = mysqli_query($mysqli, "SELECT * FROM products WHERE product_id IN ($ids_string)");

            while ($prod = mysqli_fetch_assoc($queryProduk)) {
                $pid = $prod['product_id'];
                // Ambil qty dari session
                $qty = isset($_SESSION['keranjang'][$pid]) ? $_SESSION['keranjang'][$pid] : 1;
                $price = $prod['price'];

                $queryItem = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                              VALUES ('$order_id', '$pid', '$qty', '$price')";
                mysqli_query($mysqli, $queryItem);

                // Update Stok
                mysqli_query($mysqli, "UPDATE products SET stock = stock - $qty WHERE product_id = '$pid'");
            }
        }

        // 7. KOSONGKAN KERANJANG
        unset($_SESSION['keranjang']);

        // 8. SUKSES: Kirim JSON (Bukan Redirect)
        // JavaScript yang akan menangani redirect ke selesai.php
        sendResponse("success", "Pesanan berhasil dibuat!", ["order_id" => $order_id]);

    } else {
        sendResponse("error", "Gagal menyimpan pesanan: " . mysqli_error($mysqli));
    }

} else {
    sendResponse("error", "Request tidak valid (Harus POST).");
}
?>