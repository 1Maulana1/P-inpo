

<?php
session_start();

$usersFile = __DIR__ . '/users.json';
if (!file_exists($usersFile)) {
    // create demo user if users.json missing
    $demo = [[
        'id' => 1,
        'username' => 'demo',
        'phone' => '081234567890',
        'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
        'name' => 'Demo User'
    ]];
    file_put_contents($usersFile, json_encode($demo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$users = json_decode(file_get_contents($usersFile), true) ?: [];
$error = null;
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // support form-encoded and JSON POST
    $password = '';
    if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: [];
        $identifier = $data['identifier'] ?? '';
        $password = $data['password'] ?? '';
    } else {
        $identifier = $_POST['identifier'] ?? '';
        $password = $_POST['password'] ?? '';
    }

    // find user by username or phone
    $found = null;
    foreach ($users as $u) {
        if ((isset($u['username']) && $u['username'] === $identifier) || (isset($u['phone']) && $u['phone'] === $identifier)) {
            $found = $u;
            break;
        }
    }

    if ($found && isset($found['password_hash']) && password_verify($password, $found['password_hash'])) {
        // login success
        $_SESSION['user'] = [
            'id' => $found['id'],
            'username' => $found['username'],
            'name' => $found['name'] ?? '',
            'phone' => $found['phone'] ?? ''
        ];
        // respond JSON for AJAX callers
        if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'ok', 'user' => $_SESSION['user']], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // redirect to beranda
        header('Location: ../beranda/beranda.php');
        exit;
    } else {
        $error = 'Identifier atau password salah.';
    }
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

                <form class="login-form" action="" method="post">
                    <label class="input-field">
                        <span>No. Handphone/Username</span>
                        <input type="text" name="identifier" placeholder="Masukkan nomor atau username" required value="<?php echo htmlspecialchars($identifier ?? '', ENT_QUOTES); ?>">
                    </label>

                    <label class="input-field password-field">
                        <span>Password</span>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" placeholder="Masukkan password" required>
                            <button id="togglePassword" class="toggle-password" type="button" aria-label="Tampilkan password">👁</button>
                        </div>
                    </label>

                    <?php if (!empty($error)): ?>
                        <div class="form-error" style="color:#c0392b;margin:8px 0;font-weight:600"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
                    <?php endif; ?>

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