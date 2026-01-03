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
include_once("test.php");

/* ===============================
   AMBIL DATA USER
================================ */
$userId = $_SESSION['user_id'];

$stmt = $mysqli->prepare("
    SELECT nama, email, phone, date 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$nama  = $user['nama'] ?? '';
$email = $user['email'] ?? '';
$phone = $user['phone'] ?? '';
$date  = $user['date'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-left">
            <span>netofffice · B2B Elektronik Kantor</span>
        </div>
        <div class="top-right">
            <span>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
            <span>|</span>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</div>

<!-- HEADER -->
<header class="navbar">
    <div class="container header-content">
        <div class="logo"><a href="../beranda/beranda.php">nettofice</a></div>
        <div class="search-container">
            <input type="text" placeholder="Cari elektronik kantor">
            <button class="search-btn">🔍</button>
        </div>
        <div class="cart-icon">🛒</div>
    </div>
</header>

<main class="container main-layout">
<!-- SIDEBAR -->
        
    <aside class="sidebar">
        <div class="user-brief">
            <img src="../img/default-avatar.png" id="sidebar-avatar" class="mini-avatar">
            <div class="user-details">
                <p class="username">bravesttama</p>
                <a href="#" class="edit-profile">✏️ Ubah Profil</a>
            </div>
        </div>

        <nav class="menu">
            <div class="menu-group">
                <div class="sidebar-item">
                    <span class="menu-icon">👤</span> <a href="#" class="menu-parent">Akun Saya</a>
                </div>
                <ul class="submenu">
                    <li class="sidebar-item active"><a href="#">Profil</a></li>
                    <li class="sidebar-item"><a href="bank/bank.html">Bank & Kartu</a></li>
                    <li class="sidebar-item"><a href="alamat/alamat.html">Alamat</a></a></li>
                </ul>
            </div>
            

            </div>
            <div class="sidebar-item">
                <span class="menu-icon">🔔</span> <a href="#">Notifikasi</a>
            </div>
        </nav>
    </aside>


<!-- CONTENT -->
<section class="profile-card">
    <div class="profile-header">
        <h2>Profil Saya</h2>
        <p>Kelola informasi akun Anda</p>
    </div>
    <hr>

    <form class="profile-body" method="post" action="update_profile.php">
        <div class="form-container">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <span><?= htmlspecialchars($email) ?></span>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <span><?= htmlspecialchars($phone) ?></span>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($date) ?>">
            </div>

            

            <div>
                <a href="edit.php">edit profile</a>
            </div>
            
        </div>

        <div class="avatar-upload">
            <img src="../img/default-avatar.png" class="large-avatar">
            <button type="button" class="btn-select-img">Pilih Gambar</button>
            <p class="info-text">
                Maks 1MB<br>
                JPG / PNG
            </p>
        </div>
    </form>
</section>

</main>

<script src="profile.js"></script>
</body>
</html>
