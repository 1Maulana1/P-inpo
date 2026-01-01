<?php
session_start();

// Simulasi Data User (Data Dummy)
// Nanti bisa diganti dengan ambil data dari Database
$user = [
    'username'  => 'bravesttama',
    'name'      => 'Minastitiek',
    'email'     => 'br*********@gmail.com',
    'phone'     => '0812****54',
    'shop_name' => 'Jajanan Pasar Toko Irna',
    'gender'    => 'Lainnya',
    'avatar'    => '../img/default-avatar.png'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - netofffice</title>
    <!-- Pastikan file CSS ini ada di folder yang sama (folder profil) -->
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
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
                <button type="button" class="search-btn">🔍</button>
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
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <a class="back-home" href="../beranda/beranda.php">← Kembali ke Beranda</a>
            
            <div class="user-brief">
                <img src="<?php echo $user['avatar']; ?>" id="sidebar-avatar" class="mini-avatar" alt="Avatar">
                <div class="user-details">
                    <p class="username"><?php echo $user['username']; ?></p>
                    <a href="#" class="edit-profile">✏️ Ubah Profil</a>
                </div>
            </div>

            <nav class="menu">
                <div class="menu-group">
                    <div class="sidebar-item">
                        <span class="menu-icon">👤</span> <span class="menu-parent">Akun Saya</span>
                    </div>
                    <ul class="submenu">
                        <li class="sidebar-subitem active"><a href="profile.php">Profil</a></li>
                        <li class="sidebar-subitem"><a href="bank.php">Bank & Kartu</a></li>
                        <li class="sidebar-subitem"><a href="alamat.php">Alamat</a></li>
                    </ul>
                </div>

                <div class="sidebar-item">
                    <span class="menu-icon">🔔</span> <a href="../notifikasi/notifikasi.php">Notifikasi</a>
                </div>
                
                <div class="sidebar-item">
                    <span class="menu-icon">📦</span> <a href="../pesanan/pesanan.php">Pesanan Saya</a>
                </div>

                <!-- MENU SELLER / TOKO SAYA -->
                <!-- PENTING: Pastikan folder 'seller' dan file 'index.php' sudah dibuat sejajar dengan folder 'profil' -->
                <div class="sidebar-item seller-menu">
                    <span class="menu-icon">🏪</span> 
                    <a href="../seller/index.php" style="color: #EE4D2D; font-weight: bold;">Toko Saya</a>
                </div>
            </nav>
        </aside>

        <!-- PROFILE CARD -->
        <section class="profile-card">
            <div class="profile-header">
                <h2>Profil Saya</h2>
                <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
            </div>
            <hr>

            <div class="profile-body">
                <!-- Form Kiri -->
                <div class="form-container">
                    <!-- Tambahkan method="POST" agar form berfungsi -->
                    <form id="profileForm" method="POST" onsubmit="handleSave(event)">
                        
                        <div class="form-group">
                            <label>Username</label>
                            <span style="font-weight: 500;"><?php echo $user['username']; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Nama</label>
                            <!-- Tambahkan name="fullname" -->
                            <input type="text" name="fullname" value="<?php echo $user['name']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <span><?php echo $user['email']; ?> <a href="#" style="color:#5AA9E6; margin-left:5px;">Ubah</a></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <span><?php echo $user['phone']; ?> <a href="#" style="color:#5AA9E6; margin-left:5px;">Ubah</a></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Nama Toko</label>
                            <!-- Tambahkan name="shop_name" -->
                            <input type="text" name="shop_name" value="<?php echo $user['shop_name']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Laki-laki" <?php echo ($user['gender'] == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki</label>
                                <label><input type="radio" name="gender" value="Perempuan" <?php echo ($user['gender'] == 'Perempuan') ? 'checked' : ''; ?>> Perempuan</label>
                                <label><input type="radio" name="gender" value="Lainnya" <?php echo ($user['gender'] == 'Lainnya') ? 'checked' : ''; ?>> Lainnya</label>
                            </div>
                        </div>    
                        
                        <div class="form-group">
                            <label>Tanggal lahir</label>
                            <div class="birth-date-group">
                                <select id="day" name="day"><option disabled selected>Tanggal</option></select>
                                <select id="month" name="month">
                                    <option disabled selected>Bulan</option>
                                    <option value="1">Januari</option><option value="2">Februari</option><option value="3">Maret</option>
                                    <option value="4">April</option><option value="5">Mei</option><option value="6">Juni</option>
                                    <option value="7">Juli</option><option value="8">Agustus</option><option value="9">September</option>
                                    <option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
                                </select>
                                <select id="year" name="year"><option disabled selected>Tahun</option></select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">Simpan</button>
                    </form>
                </div>

                <!-- Upload Avatar Kanan -->
                <div class="avatar-upload">
                    <img src="<?php echo $user['avatar']; ?>" alt="Profile" class="large-avatar" id="display-avatar">
                    
                    <input type="file" id="file-input" name="avatar" accept=".jpg, .jpeg, .png" style="display: none;" onchange="previewImage(this)">
                    
                    <button type="button" class="btn-select-img" onclick="document.getElementById('file-input').click()">Pilih Gambar</button>
                    
                    <p class="info-text">Ukuran gambar: maks. 1 MB<br>Format gambar: .JPEG, .PNG</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Success -->
    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <div class="success-icon">✔️</div>
            <p>Berhasil diperbarui</p>
            <button onclick="closeModal()" class="btn-close-modal" style="margin-top:10px; padding:5px 15px; cursor:pointer;">OK</button>
        </div>
    </div>

    <!-- Script JS -->
    <script src="profile.js"></script>
</body>
</html>