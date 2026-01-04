<?php
session_start();

// 1. KONEKSI
include_once("test.php");

// 2. CEK DATA POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];
    $nama = mysqli_real_escape_string($mysqli, $_POST['nama_penerima']);
    $telp = mysqli_real_escape_string($mysqli, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($mysqli, $_POST['alamat']);
    $metode = mysqli_real_escape_string($mysqli, $_POST['metode_pembayaran']);
    $total = $_POST['total_hidden'];
    $tanggal = date('Y-m-d H:i:s');

    // 3. INSERT KE TABEL ORDERS (Header)
    // Menggabungkan alamat dengan nama penerima untuk kejelasan
    $full_address = "Penerima: $nama ($telp) - Alamat: $alamat";
    
    $queryOrder = "INSERT INTO orders (user_id, order_date, total_amount, status, shipping_address, payment_method) 
                   VALUES ('$user_id', '$tanggal', '$total', 'Pending', '$full_address', '$metode')";
    
    if (mysqli_query($mysqli, $queryOrder)) {
        // Ambil ID Order yang baru saja dibuat
        $order_id = mysqli_insert_id($mysqli);

        // 4. INSERT KE TABEL ORDER_ITEMS (Detail Barang)
        $ids = implode(',', array_keys($_SESSION['keranjang']));
        $queryProduk = mysqli_query($mysqli, "SELECT * FROM products WHERE product_id IN ($ids)");

        while ($prod = mysqli_fetch_assoc($queryProduk)) {
            $pid = $prod['product_id'];
            $qty = $_SESSION['keranjang'][$pid];
            $price = $prod['price']; // Harga saat beli (penting disimpan jika nanti harga berubah)

            // Masukkan detail
            $queryItem = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                          VALUES ('$order_id', '$pid', '$qty', '$price')";
            mysqli_query($mysqli, $queryItem);

            // Opsional: Kurangi Stok Produk
            $updateStok = "UPDATE products SET stock = stock - $qty WHERE product_id = '$pid'";
            mysqli_query($mysqli, $updateStok);
        }

        // 5. KOSONGKAN KERANJANG
        unset($_SESSION['keranjang']);

        // 6. REDIRECT KE HALAMAN SUKSES
        header("Location: selesai.php?order_id=$order_id");
        exit();

    } else {
        echo "Gagal membuat pesanan: " . mysqli_error($mysqli);
    }

} else {
    // Jika akses langsung tanpa form
    header("Location: pembayaran.php");
}
?>