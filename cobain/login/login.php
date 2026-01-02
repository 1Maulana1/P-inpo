<?php
session_start();
include_once("test.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = trim($_POST['identifier']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare(
        "SELECT id, nama, password_hash FROM users 
         WHERE email = ? OR phone = ? LIMIT 1"
    );
    $stmt->bind_param("ss", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "❌ Email/No HP atau password salah";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>netofffice - Log in</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <a class="back-home" href="../beranda/beranda.html">← Kembali ke Beranda</a>
    <header class="site-header">
        <div class="main-header">
            <div class="logo">netofffice</div>
        </div>
    </header>

    <main class="page">
        <section class="hero">
            <div class="hero-brand">
                <div class="brand-badge">
                    <div class="bag-icon">
                        <div class="bag-handle"></div>
                        <div class="bag-letter">N</div>
                    </div>
                </div>
                <div class="brand-copy">
                    <h1>netofffice</h1>
                    <p>Elektronik kantor lebih efisien</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Log in</h2>
                </div>

                <form class="login-form" action="#" method="post">
                    <label class="input-field">
                        <span>No. Handphone/Username</span>
                        <input type="text" name="identifier" placeholder="Masukkan nomor atau username" required>
                    </label>

                    <label class="input-field password-field">
                        <span>Password</span>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" placeholder="Masukkan password" required>
                            <button id="togglePassword" class="toggle-password" type="button" aria-label="Tampilkan password">👁</button>
                        </div>
                    </label>

                    <button class="primary-btn" type="submit">LOG IN</button>

                    <div class="links-row">
                        <a href="./lupa password/lupapw.html">Lupa Password</a>
                        <span class="separator">|</span>
                        <a href="./signup/signup.html">Daftar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="login.js"></script>
</body>
</html>