<?php
session_start();

// Simulasi Data User (Sama seperti di profile.php)
$user = [
    'username'  => 'bravesttama',
    'name'      => 'Minastitiek',
    'email'     => 'br*********@gmail.com',
    'phone'     => '0812****54',
    'shop_name' => 'Jajanan Pasar Toko Irna',
    'avatar'    => '../img/default-avatar.png'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank & Kartu - netofffice</title>
    <!-- CSS Utama Profile -->
    <link rel="stylesheet" href="profile.css">
    <!-- CSS Khusus Halaman Bank -->
    <link rel="stylesheet" href="bank.css">
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <a href="../notifikasi/notifikasi.php">🔔 Notifikasi</a>
                <a href="#">❓ Bantuan</a>
                <span style="margin: 0 5px;">|</span>
                <!-- Link Logout -->
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="navbar">
        <div class="container header-content">
            <div class="logo">netofffice</div>
            <div class="search-container">
                <input type="text" placeholder="Cari elektronik kantor di netofffice">
                <button type="submit" class="search-btn">🔍</button>
            </div>
            <!-- Link Keranjang -->
            <div class="cart-icon">
                <a href="../keranjang/keranjang.php" style="text-decoration:none; color:inherit;">
                    🛒 <span class="badge">0</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-layout">
        
        <!-- SIDEBAR (Konsisten dengan Profile) -->
        <aside class="sidebar">
            <a class="back-home" href="../beranda/beranda.php">← Kembali ke Beranda</a>
            
            <div class="user-brief">
                <img src="<?php echo $user['avatar']; ?>" id="sidebar-avatar" class="mini-avatar" alt="Avatar">
                <div class="user-details">
                    <p class="username"><?php echo $user['username']; ?></p>
                    <a href="profile.php" class="edit-profile">✏️ Ubah Profil</a>
                </div>
            </div>

            <nav class="menu">
                <div class="menu-group">
                    <div class="sidebar-item">
                        <span class="menu-icon">👤</span> <span class="menu-parent">Akun Saya</span>
                    </div>
                    <ul class="submenu">
                        <li class="sidebar-subitem"><a href="profile.php">Profil</a></li>
                        <li class="sidebar-subitem active"><a href="bank.php">Bank & Kartu</a></li>
                        <li class="sidebar-subitem"><a href="alamat.php">Alamat</a></li>
                    </ul>
                </div>

                <div class="sidebar-item">
                    <span class="menu-icon">🔔</span> <a href="../notifikasi/notifikasi.php">Notifikasi</a>
                </div>
                
                <div class="sidebar-item">
                    <span class="menu-icon">📦</span> <a href="../pesanan/pesanan.php">Pesanan Saya</a>
                </div>

                <!-- MENU SELLER -->
                <div class="sidebar-item seller-menu">
                    <span class="menu-icon">🏪</span> 
                    <a href="../seller/index.php" style="color: #EE4D2D; font-weight: bold;">Toko Saya</a>
                </div>
            </nav>
        </aside>

        <!-- KONTEN UTAMA: BANK & KARTU -->
        <section class="profile-card">
            <div class="profile-header card-flex">
                <div>
                    <h2>Kartu Kredit / Debit</h2>
                </div>
                <button class="btn-add-card" onclick="alert('Fitur tambah kartu akan segera hadir!')">+ Tambahkan Kartu Baru</button>
            </div>
            <hr>
            
            <div class="bank-content">
                <!-- State Kosong (Default) -->
                <div class="empty-state">
                    <div class="empty-icon">💳</div>
                    <p>Kamu belum memiliki kartu yang terdaftar.</p>
                </div>

                <!-- Contoh jika ada kartu (Disembunyikan, uncomment untuk preview) -->
                <!--
                <div class="card-list">
                    <div class="credit-card">
                        <div class="card-logo">VISA</div>
                        <div class="card-number">**** **** **** 4242</div>
                        <div class="card-actions">
                            <button class="btn-delete">Hapus</button>
                        </div>
                    </div>
                </div>
                -->
            </div>
            
            <!-- Bagian Rekening Bank -->
            <div class="profile-header card-flex" style="margin-top: 30px;">
                <div>
                    <h2>Rekening Bank</h2>
                </div>
                <button class="btn-add-card" onclick="alert('Fitur tambah rekening akan segera hadir!')">+ Tambahkan Rekening Bank</button>
            </div>
            <hr>

            <div class="bank-content">
                <div class="empty-state">
                    <div class="empty-icon">🏦</div>
                    <p>Kamu belum memiliki rekening bank yang terdaftar.</p>
                </div>
            </div>

        </section>
    </main>

    <script src="bank.js"></script> 
</body>
</html>