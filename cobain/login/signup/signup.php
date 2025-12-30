<?php

session_start();

try {
    $dbPath = __DIR__ . '/../../../server/users.db'; // ../.. up to P-inpo then server/users.db
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) mkdir($dbDir, 0755, true);

    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure table exists
    $db->exec("CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE,
      email TEXT UNIQUE,
      password_hash TEXT,
      name TEXT,
      phone TEXT,
      avatar_url TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    http_response_code(500);
    echo 'Database error';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['password_confirm'] ?? '';
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo 'Missing required fields';
    exit;
}
if ($password !== $confirm) {
    http_response_code(400);
    echo 'Password mismatch';
    exit;
}

// Check uniqueness
$stmt = $db->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    http_response_code(400);
    echo 'Username or email already used';
    exit;
}

// Optional avatar upload
$avatarUrl = null;
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $f = $_FILES['avatar'];
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($f['type'], $allowed) || $f['size'] > 2 * 1024 * 1024) {
        http_response_code(400);
        echo 'Invalid avatar';
        exit;
    }
    $uploadDir = __DIR__ . '/../../../server/uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $uploadDir . '/' . $filename;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        http_response_code(500);
        echo 'Failed to save avatar';
        exit;
    }
    // relative path used by frontend (adjust if needed)
    $avatarUrl = 'server/uploads/' . $filename;
}

// Hash and insert
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $db->prepare('INSERT INTO users (username,email,password_hash,name,phone,avatar_url) VALUES (?,?,?,?,?,?)');
try {
    $insert->execute([$username, $email, $hash, $name ?: null, $phone ?: null, $avatarUrl]);
    $_SESSION['user_id'] = $db->lastInsertId();
    // redirect back to beranda
    header('Location: ../../beranda/beranda.html');
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo 'Failed to create account';
    exit;
}
?>
```// filepath: c:\Users\LENOVO\Documents\P-inpo\cobain\login\signup\signup.php
<?php
session_start();

try {
    $dbPath = __DIR__ . '/../../../server/users.db'; // ../.. up to P-inpo then server/users.db
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) mkdir($dbDir, 0755, true);

    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure table exists
    $db->exec("CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE,
      email TEXT UNIQUE,
      password_hash TEXT,
      name TEXT,
      phone TEXT,
      avatar_url TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    http_response_code(500);
    echo 'Database error';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['password_confirm'] ?? '';
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo 'Missing required fields';
    exit;
}
if ($password !== $confirm) {
    http_response_code(400);
    echo 'Password mismatch';
    exit;
}

// Check uniqueness
$stmt = $db->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    http_response_code(400);
    echo 'Username or email already used';
    exit;
}

// Optional avatar upload
$avatarUrl = null;
if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $f = $_FILES['avatar'];
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($f['type'], $allowed) || $f['size'] > 2 * 1024 * 1024) {
        http_response_code(400);
        echo 'Invalid avatar';
        exit;
    }
    $uploadDir = __DIR__ . '/../../../server/uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $uploadDir . '/' . $filename;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        http_response_code(500);
        echo 'Failed to save avatar';
        exit;
    }
    // relative path used by frontend (adjust if needed)
    $avatarUrl = 'server/uploads/' . $filename;
}

// Hash and insert
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $db->prepare('INSERT INTO users (username,email,password_hash,name,phone,avatar_url) VALUES (?,?,?,?,?,?)');
try {
    $insert->execute([$username, $email, $hash, $name ?: null, $phone ?: null, $avatarUrl]);
    $_SESSION['user_id'] = $db->lastInsertId();
    // redirect back to beranda
    header('Location: ../../beranda/beranda.html');
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo 'Failed to create account';
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daftar</title>
	<link rel="stylesheet" href="signup.css">
</head>
<body>
	<header class="site-header">
		<div class="brand">Shopee</div>
		<div class="title">Daftar Akun</div>
	</header>

	<main class="page">
		<section class="card">
			<h1>Daftar</h1>
			<form class="form" action="#" method="post" enctype="multipart/form-data">
				<label class="field">
					<span>Username</span>
					<input type="text" name="username" placeholder="Username" required>
				</label>

				<label class="field">
					<span>Nama</span>
					<input type="text" name="nama" placeholder="Nama lengkap" required>
				</label>

				<label class="field">
					<span>Email</span>
					<input type="email" name="email" placeholder="nama@email.com" required>
				</label>

				<label class="field">
					<span>No. Telp</span>
					<input type="tel" name="telp" placeholder="08xxxxxxxxxx" required>
				</label>

				<div class="field">
					<span>Jenis Kelamin</span>
					<div class="radio-group">
						<label><input type="radio" name="gender" value="male" required> Laki-laki</label>
						<label><input type="radio" name="gender" value="female"> Perempuan</label>
						<label><input type="radio" name="gender" value="other"> Lainnya</label>
					</div>
				</div>

				<label class="field">
					<span>Tanggal Lahir</span>
					<input type="date" name="dob" required>
				</label>

				<label class="field file-field">
					<span>Foto Profil</span>
					<div class="file-row">
						<input id="avatar" type="file" name="avatar" accept="image/png, image/jpeg">
						<span id="fileName" class="file-name">Belum ada file</span>
					</div>
					<div class="preview" id="preview"></div>
				</label>

				<button class="primary-btn" type="submit">Simpan</button>
			</form>
		</section>
	</main>

	<script src="signup.js"></script>
</body>
</html>
