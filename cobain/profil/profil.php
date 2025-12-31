<?php
session_start();

$usersFile = __DIR__ . '/users.json';
if (!file_exists($usersFile)) {
    $demo = [[
        'id' => 1,
        'username' => 'demo',
        'name' => 'Demo User',
        'email' => 'demo@example.com',
        'phone' => '081234567890',
        'shopName' => '',
        'gender' => '',
        'birth' => '',
        'avatar' => ''
    ]];
    file_put_contents($usersFile, json_encode($demo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$users = json_decode(file_get_contents($usersFile), true) ?: [];
$currentUser = $_SESSION['user'] ?? ($users[0] ?? null);

// Handle profile update POST (supports form multipart or JSON)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isJson = stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;
    $data = $isJson ? (json_decode(file_get_contents('php://input'), true) ?: []) : $_POST;

    if (!$currentUser) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }

    // Find user in list
    $updated = false;
    foreach ($users as $i => $u) {
        if ($u['id'] == $currentUser['id']) {
            // Update allowed fields
            $users[$i]['name'] = $data['name'] ?? $u['name'];
            $users[$i]['email'] = $data['email'] ?? $u['email'] ?? '';
            $users[$i]['phone'] = $data['phone'] ?? $u['phone'] ?? '';
            $users[$i]['shopName'] = $data['shopName'] ?? $u['shopName'] ?? '';
            $users[$i]['gender'] = $data['gender'] ?? $u['gender'] ?? '';
            // birth can come as day/month/year or as single field
            if (isset($data['birth'])) {
                $users[$i]['birth'] = $data['birth'];
            } else {
                $day = $data['day'] ?? null; $month = $data['month'] ?? null; $year = $data['year'] ?? null;
                if ($day && $month && $year) $users[$i]['birth'] = "$day-$month-$year";
            }

            // Handle avatar upload if present (form multipart)
            if (!empty($_FILES['avatar']['tmp_name'])) {
                $file = $_FILES['avatar'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    if ($file['size'] <= 1024*1024) { // <=1MB
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $file['tmp_name']);
                        finfo_close($finfo);
                        if (in_array($mime, ['image/jpeg','image/png','image/jpg'])) {
                            $uploadsDir = __DIR__ . '/uploads';
                            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
                            $ext = $mime === 'image/png' ? 'png' : 'jpg';
                            $filename = 'avatar_' . $u['id'] . '_' . time() . '.' . $ext;
                            $dest = $uploadsDir . '/' . $filename;
                            if (move_uploaded_file($file['tmp_name'], $dest)) {
                                // store web-relative path
                                $users[$i]['avatar'] = 'uploads/' . $filename;
                            }
                        }
                    }
                }
            }

            $updated = true;
            $currentUser = $users[$i];
            $_SESSION['user'] = $currentUser;
            break;
        }
    }

    if ($updated) {
        file_put_contents($usersFile, json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        // Respond JSON for AJAX
        if ($isJson || stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'ok', 'user' => $currentUser], JSON_UNESCAPED_UNICODE);
            exit;
        }
        // Otherwise redirect back to profile page (PRG)
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$currentUserJson = json_encode($currentUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>
            <div class="top-right">
                <a href="../login/signup/signup.html">Daftar</a>
                <span>|</span>
                <a href="../login/login.html">Log In</a>
            </div>
        </div>
    </div>

    <header class="navbar">
        <div class="container header-content">
            <div class="logo">netofffice</div>
            <div class="search-container">
                <input type="text" placeholder="Cari elektronik kantor di netofffice">
                <button type="submit" class="search-btn">🔍</button>
            </div>
            <div class="cart-icon">🛒 <span class="badge"></span></div>
        </div>
    </header>
    <main class="container main-layout">
        
<aside class="sidebar">
    <a class="back-home" href="../beranda/beranda.html" style="display: block; margin-bottom: 16px;">← Kembali ke Beranda</a>
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
                <li class="sidebar-item"><a href="bank.html">Bank & Kartu</a></li>
                <li class="sidebar-item"><a href="alamat.html">Alamat</a></a></li>
                <li class="sidebar-item"><a href="#">Ubah Password</a></li>
            </ul>
        </div>
        
        <div class="sidebar-item">
            <span class="menu-icon">📋</span> <a href="#">Pesanan Saya</a>
        </div>
        <div class="sidebar-item">
            <span class="menu-icon">🔔</span> <a href="#">Notifikasi</a>
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
                    <div class="form-group">
                        <label>Username</label>
                        <span>bravesttama</span>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" value="Minastitiek">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <span>br*********@gmail.com <a href="#">Ubah</a></span>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <span>**********54 <a href="#">Ubah</a></span>
                    </div>
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="text" value="Jajanan Pasar Toko Irna">
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="radio-group">
                            <input type="radio" name="gender"> Laki-laki
                            <input type="radio" name="gender"> Perempuan
                            <input type="radio" name="gender" checked> Lainnya
                        </div>
                    </div>    
                <div class="form-group">
                    <label>Tanggal lahir</label>
                    <div class="birth-date-group">
                        <select id="day">
                            <option disabled selected>Tanggal</option>
                        </select>

                    <select id="month">
                        <option disabled selected>Bulan</option>
                        <option>Januari</option>
                        <option>Februari</option>
                        <option>Maret</option>
                        <option>April</option>
                        <option>Mei</option>
                        <option>Juni</option>
                        <option>Juli</option>
                        <option>Agustus</option>
                        <option>September</option>
                        <option>Oktober</option>
                        <option>November</option>
                        <option>Desember</option>
                    </select>

                    <select id="year">
                        <option disabled selected>Tahun</option>
                        </select>
                    </div>

                    </div>
                    <button class="btn-save">Simpan</button>
                </div>

                <div class="avatar-upload">
                    <img src="https://via.placeholder.com/100" alt="Profile" class="large-avatar" id="display-avatar">
    
                    <input type="file" id="file-input" name="avatar" accept=".jpg, .jpeg, .png" style="display: none;">
    
                    <button type="button" class="btn-select-img" onclick="document.getElementById('file-input').click()">Pilih Gambar</button>
    
                    <p class="info-text">Ukuran gambar: maks. 1 MB<br>Format gambar: .JPEG, .PNG</p>
                </div>
            </div>
        </section>
    </main>

</body>
    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <div class="success-icon">✔️</div>
            <p>Berhasil diperbarui</p>
        </div>
    </div>
<?php
// Inject currentUser for profile.js
echo "<script>var currentUser = $currentUserJson;</script>\n";
?>
<script src="profile.js"></script>
</body>
</html>