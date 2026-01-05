<?php
session_start();

/* ===============================
   CEK LOGIN (WAJIB)
================================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

/* ===============================
   KONEKSI DATABASE
================================ */
// Pastikan path ini benar sesuai struktur folder kamu
include_once("../login/test.php"); 

/* ===============================
   AMBIL DATA USER
================================ */
$userId = $_SESSION['user_id'];

$stmt = $mysqli->prepare("
    SELECT nama, email, phone, date, avatar
    FROM users 
    WHERE user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Gunakan Null Coalescing Operator untuk menghindari error jika data kosong
$nama  = $user['nama'] ?? '';
$email = $user['email'] ?? '';
$phone = $user['phone'] ?? '';
$date  = $user['date'] ?? '';
$avatar = $user['avatar'] ?? 'default-avatar.png'; // Fallback avatar
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - netofffice</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <a href="/cobain/login/login.php">Logout</a>
            </div>
        </div>
    </div>

    <header class="navbar">
        <div class="container header-content">
            <div class="logo"> 
                <a href="/cobain/beranda/beranda.php" style="color: #ffffff; text-decoration: none;">netofffice</a>
            </div>
            <div class="search-container">
                <input type="text" placeholder="Cari elektronik kantor di netofffice">
                <button type="button" class="search-btn">🔍</button>
            </div>
            <div class="cart-icon">
                <a href="../keranjang/keranjang.php" style="text-decoration:none; color:inherit;">
                    🛒 <span class="badge">0</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container main-layout">
        
        <aside class="sidebar">
            <div class="user-brief">
                <img src="../uploads/<?php echo htmlspecialchars($avatar); ?>" id="sidebar-avatar" class="mini-avatar" alt="Avatar">
                <div class="user-details">
                    <p class="username"><?php echo htmlspecialchars($nama); ?></p>
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
                        
                        <li class="sidebar-subitem"><a href="bank/bank.php">Bank & Kartu</a></li>
                        
                        <li class="sidebar-subitem"><a href="alamat/alamat.php">Alamat</a></li>
                    </ul>
                </div>
                
                <div class="sidebar-item">
                    <span class="menu-icon">📦</span> <a href="../pesanan/pesanan.php">Pesanan Saya</a>
                </div>

                <div class="sidebar-item seller-menu">
                    <span class="menu-icon">🏪</span> 
                    <a href="../seller/dashboard_seller.php" style="color: #EE4D2D; font-weight: bold;">Toko Saya</a>
                </div>
            </nav>
        </aside>

        <section class="profile-card">
            <div class="profile-header">
                <h2>Profil Saya</h2>
                <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
            </div>
            <hr>

            <div class="profile-body">
                <div class="form-container">
                    <form id="profileForm" method="POST" onsubmit="handleSave(event)">
                        
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <span><?= htmlspecialchars($email) ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>No Telepon</label>
                            <span><?= htmlspecialchars($phone) ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="<?= htmlspecialchars($date) ?>">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>

                <div class="avatar-upload">
                    <input type="file" id="file-input" name="avatar" accept=".jpg, .jpeg, .png" style="display: none;" onchange="previewImage(this)">
                    <button type="button" class="btn-select-img" onclick="document.getElementById('file-input').click()">Pilih Gambar</button>
                    <p class="info-text">Ukuran gambar: maks. 1 MB<br>Format gambar: .JPEG, .PNG</p>
                </div>
            </div>
        </section>
    </main>

    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <div class="success-icon">✔️</div>
            <p>Berhasil diperbarui</p>
            <button onclick="closeModal()" class="btn-close-modal" style="margin-top:10px; padding:5px 15px; cursor:pointer;">OK</button>
        </div>
    </div>

    <script src="profile.js"></script>
</body>
</html>