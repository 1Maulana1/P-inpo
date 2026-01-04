<?php
session_start();

// Simulasi Data User
$user = [
    'username'  => 'bravesttama',
    'name'      => 'Minastitiek',
    'email'     => 'br*********@gmail.com',
    'phone'     => '0812****54',
    'shop_name' => 'Jajanan Pasar Toko Irna',
    'avatar'    => '../../img/default-avatar.png' // Mundur 2 folder untuk gambar juga
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alamat Saya - netofffice</title>
    <link rel="stylesheet" href="../profile.css"> 
    <link rel="stylesheet" href="alamat.css">
</head>
<body>

    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <a href="../../notifikasi/notifikasi.php">🔔 Notifikasi</a> <a href="#">❓ Bantuan</a>
                <span style="margin: 0 5px;">|</span>
                <a href="../../login/login.php" style="text-decoration: none; color: white;">Logout</a> </div>
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
                        <li class="sidebar-subitem"><a href="../bank/bank.php" style="text-decoration: none;">Bank & Kartu</a></li>
                        <li class="sidebar-subitem active"><a href="alamat.php" style="text-decoration: none;">Alamat</a></li>
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
            <div class="address-header">
                <h2>Alamat Saya</h2>
                <button class="btn-add-address" id="openModal">+ Tambah Alamat Baru</button>
            </div>
            <hr>

            <div class="address-list">
                <h3>Alamat</h3>
                
                <div class="address-item">
                    <div class="address-info">
                        <p><strong><?php echo $user['name']; ?></strong> | (+62) 813 3333 0000 <a href="#" class="action-link">Ubah</a></p>
                        <p class="address-text">Wahana pondok gede A6 nomer 1.</p>
                        <p class="address-city">WAKANDA FOREVER</p>
                        <div class="badges">
                            <span class="badge-primary">Utama</span> 
                            <span class="badge-secondary">Alamat Toko</span>
                        </div>
                    </div>
                    <button class="btn-set-default" disabled>Atur sebagai utama</button>
                </div>
                
                <hr>
                
                <div class="address-item">
                    <div class="address-info">
                        <p><strong>Kost Pak Santoso</strong> | (+62) 228 1398 9004 <a href="#" class="action-link">Ubah</a> <a href="#" class="action-link delete">Hapus</a></p>
                        <p class="address-text">Kost Tingki WIngki, Jalan Nglanjaran No. 78, RT.8/RW.17, Candirejo, Ngaglik</p>
                        <p class="address-city">NGAGLIK, KAB. SLEMAN, DI YOGYAKARTA, ID, 55581</p>
                    </div>
                    <button class="btn-set-default">Atur sebagai utama</button>
                </div>
            </div>
        </section>
    </main>

    <div id="addressModal" class="modal-overlay">
        <div class="modal-address">
            <h3>Alamat Baru</h3>
            <form>
                <div class="form-row">
                    <input type="text" placeholder="Nama Lengkap" class="input-half">
                    <input type="text" placeholder="Nomor Telepon" class="input-half">
                </div>
                
                <div class="form-group">
                    <select class="input-full">
                        <option>Provinsi, Kota, Kecamatan, Kode Pos</option>
                        <option>DKI Jakarta, Jakarta Selatan, Tebet, 12810</option>
                        <option>Jawa Barat, Bekasi, Bekasi Barat, 17145</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea placeholder="Nama Jalan, Gedung, No. Rumah" class="input-full"></textarea>
                </div>
                
                <div class="form-group">
                    <input type="text" placeholder="Detail Lainnya (Cth: Blok / Unit No., Patokan)" class="input-full">
                </div>
                
                <div class="map-placeholder">
                    <button type="button">+ Tambah Lokasi Peta</button>
                </div>

                <p class="label-tag">Tandai Sebagai:</p>
                <div class="tag-buttons">
                    <button type="button" class="btn-tag">Rumah</button>
                    <button type="button" class="btn-tag">Kantor</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="closeModal">Nanti Saja</button>
                    <button type="submit" class="btn-ok">OK</button>
                </div>
            </form>
        </div>
    </div>

    <script src="alamat.js"></script>
</body>
</html>