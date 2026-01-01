<?php
session_start();

// Simulasi Data User (Agar sidebar tidak kosong)
// Di aplikasi asli, data ini diambil dari session/database saat login
$user = [
    'username' => $_SESSION['username'] ?? 'User',
    'is_logged_in' => isset($_SESSION['user_id']) || true // Set true untuk preview tampilan
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - netofffice</title>
    <!-- CSS dan JS dipanggil sesuai permintaan -->
    <link rel="stylesheet" href="pesanan.css">
    <script src="pesanan.js" defer></script>
    
    <!-- Sedikit style tambahan untuk sidebar agar rapi jika CSS utama belum mengatur list -->
    <style>
        .sidebar-nav ul { list-style: none; padding: 0; }
        .sidebar-nav li { padding: 10px 0; }
        .sidebar-nav a { text-decoration: none; color: #333; display: block; }
        .sidebar-nav a:hover, .sidebar-nav li.active a { color: #5AA9E6; font-weight: bold; }
    </style>
</head>

<body class="min-h-screen flex flex-col">

    <header>
        <div class="top-header"> 
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>

            <div class="top-right">
                <?php if ($user['is_logged_in']): ?>
                    <a href="../profil/profile.php">Halo, <?php echo $user['username']; ?></a>
                    <span>|</span>
                    <a href="../logout.php">Logout</a>
                <?php else: ?>
                    <a href="../login/signup/signup.php">Daftar</a>
                    <span>|</span>
                    <a href="../login/login.php">Log In</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="main-header">
            <div class="logo">netofffice</div>
            <div class="search-box"> 
                <input type="text" placeholder="Cari elektronik kantor">
                <button> 🔍 </button>
            </div>
            <div class="cart-icon">
                <a href="../keranjang/keranjang.php" style="text-decoration:none; color:inherit;">🛒</a>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <aside class="sidebar">
            <!-- Menambahkan info user ringkas di sidebar -->
            <div class="user-brief" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                <strong><?php echo $user['username']; ?></strong>
                <br>
                <a href="../profil/profile.php" style="font-size: 12px; color: #888;">Ubah Profil</a>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="../profil/profile.php">👤 Akun Saya</a></li>
                    <li class="active"><a href="pesanan.php">📦 Pesanan Saya</a></li>
                    <li><a href="../notifikasi/notifikasi.php">🔔 Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <div class="tabs-wrapper">
                <a class="back-home" href="../beranda/beranda.php">← Kembali</a>
                
                <!-- Container Tab akan diisi otomatis oleh pesanan.js -->
                <!-- Saya tambahkan fallback HTML statis agar tidak kosong jika JS belum load -->
                <div id="tabs-container" class="tabs-container hide-scroll">
                    <div class="tab-item active" data-status="all">Semua</div>
                    <div class="tab-item" data-status="unpaid">Belum Bayar</div>
                    <div class="tab-item" data-status="packed">Dikemas</div>
                    <div class="tab-item" data-status="sent">Dikirim</div>
                    <div class="tab-item" data-status="completed">Selesai</div>
                </div>
            </div>
            
            <!-- Daftar Pesanan akan diisi otomatis oleh pesanan.js -->
            <div id="orders-list" class="orders-list">
                <p style="text-align: center; color: #999; padding: 20px;">Memuat data pesanan...</p>
            </div>
        </div>
    </div>

</body>
</html>