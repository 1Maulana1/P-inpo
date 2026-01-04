<?php
session_start();

// Simulasi Data User
$user = [
    'username'  => 'bravesttama',
    'name'      => 'Minastitiek',
    'email'     => 'br*********@gmail.com',
    'phone'     => '0812****54',
    'shop_name' => 'Jajanan Pasar Toko Irna',
    'avatar'    => '../../img/default-avatar.png'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank & Kartu - netofffice</title>
    <link rel="stylesheet" href="../profile.css"> 
    <link rel="stylesheet" href="bank.css">
</head>
<body>

    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <a href="../../notifikasi/notifikasi.php">🔔 Notifikasi</a>
                <a href="#">❓ Bantuan</a>
                <span style="margin: 0 5px;">|</span>
                <a href="../../login/login.php" style="text-decoration: none; color: white;">Logout</a>
            </div>
        </div>
    </div>

    <header class="navbar">
        <div class="container header-content">
            <div class="logo"> 
                <a href="../../beranda/beranda.php" style="color: #ffffff; text-decoration: none;">netofffice</a>
            </div>
            <div class="search-container">
                <input type="text" placeholder="Cari elektronik kantor di netofffice">
                <button type="submit" class="search-btn">🔍</button>
            </div>
            <div class="cart-icon">
                <a href="../../keranjang/keranjang.php" style="text-decoration:none; color:inherit;">
                    🛒 <span class="badge">0</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container main-layout">
        
        <aside class="sidebar">
            <div class="user-brief">
                <img src="<?php echo $user['avatar']; ?>" id="sidebar-avatar" class="mini-avatar" alt="Avatar">
                <div class="user-details">
                    <p class="username"><?php echo $user['username']; ?></p>
                    <a href="../profile.php" class="edit-profile" style="text-decoration: none;">✏️ Ubah Profil</a>
                </div>
            </div>

            <nav class="menu">
                <div class="menu-group">
                    <div class="sidebar-item">
                        <span class="menu-icon">👤</span> <span class="menu-parent">Akun Saya</span>
                    </div>
                    <ul class="submenu">
                        <li class="sidebar-subitem"><a href="../profile.php" style="text-decoration: none;">Profil</a></li>
                        <li class="sidebar-subitem active"><a href="bank.php" style="text-decoration: none;">Bank & Kartu</a></li>
                        <li class="sidebar-subitem"><a href="../alamat/alamat.php" style="text-decoration: none;">Alamat</a></li>
                    </ul>
                </div>
                
                <div class="sidebar-item">
                    <span class="menu-icon">📦</span> <a href="../../pesanan/pesanan.php" style="text-decoration: none; color: inherit;">Pesanan Saya</a>
                </div>

                <div class="sidebar-item seller-menu">
                    <span class="menu-icon">🏪</span> 
                    <a href="../../seller/index.php" style="color: #EE4D2D; font-weight: bold; text-decoration: none;">Toko Saya</a>
                </div>
            </nav>
        </aside>

        <section class="profile-card">
            
            <div class="bank-header">
                <h2>Kartu Kredit / Debit</h2>
                <button class="btn-add-card">+ Tambahkan Kartu Baru</button>
            </div>
            <hr>

            <div class="bank-list">
                <div class="empty-state">
                    <div class="empty-icon">💳</div>
                    <p>Kamu belum memiliki kartu yang terdaftar.</p>
                </div>
            </div>

            <br><br>

            <div class="bank-header">
                <h2>Rekening Bank</h2>
                <button class="btn-add-card">+ Tambahkan Rekening</button>
            </div>
            <hr>

            <div class="bank-list">
                <div class="empty-state">
                    <div class="empty-icon">🏦</div>
                    <p>Kamu belum memiliki rekening bank.</p>
                </div>
            </div>

        </section>
    </main>

    <script src="bank.js"></script>
</body>
</html>