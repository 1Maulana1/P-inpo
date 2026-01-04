<?php
session_start();

// 1. Inisialisasi Keranjang
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// 2. Ambil data
$act = isset($_GET['act']) ? $_GET['act'] : '';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 3. Logika Tambah (+1)
if ($act == 'tambah' && $product_id > 0) {
    if (isset($_SESSION['keranjang'][$product_id])) {
        $_SESSION['keranjang'][$product_id] += 1;
    } else {
        $_SESSION['keranjang'][$product_id] = 1;
    }
    
    // Redirect pintar (kembali ke halaman sebelumnya)
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: keranjang.php");
    }
    exit();

}
// 4. Logika Kurang (-1) [BARU DITAMBAHKAN]
elseif ($act == 'kurang' && $product_id > 0) {
    if (isset($_SESSION['keranjang'][$product_id])) {
        // Jika jumlah lebih dari 1, kurangi 1
        if ($_SESSION['keranjang'][$product_id] > 1) {
            $_SESSION['keranjang'][$product_id] -= 1;
        } else {
            // Jika sisa 1 dan dikurangi, hapus item tersebut
            unset($_SESSION['keranjang'][$product_id]);
        }
    }
    header("Location: keranjang.php");
    exit();

} 
// 5. Logika Hapus Item (Tong Sampah)
elseif ($act == 'hapus' && $product_id > 0) {
    if (isset($_SESSION['keranjang'][$product_id])) {
        unset($_SESSION['keranjang'][$product_id]);
    }
    header("Location: keranjang.php");
    exit();

} 
// 6. Logika Kosongkan Semua
elseif ($act == 'clear') {
    unset($_SESSION['keranjang']);
    header("Location: keranjang.php");
    exit();
}

header("Location: keranjang.php");
?>