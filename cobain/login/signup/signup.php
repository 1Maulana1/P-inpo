    <?php
    if (isset($_POST['Submit'])) {
        include_once("test.php"); // koneksi DB

    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $date   = $_POST['date'];
    $alamat = $_POST['alamat'];
    $gender = $_POST['gender'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $avatar = "";

    // 🔍 CEK EMAIL
    $cek = mysqli_query($mysqli, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "❌ Email sudah terdaftar";
        exit;
    }

    // ➕ INSERT
    $query = "
        INSERT INTO users
        (nama,email,phone,password_hash,gender,date,alamat,avatar)
        VALUES
        ('$nama','$email','$phone','$password_hash','$gender','$date','$alamat','$avatar')
    ";
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
					<input type="tel" name="telp" placeholder="08..." required>
				</label>

				<label class="field">
					<span>Tanggal Lahir</span>
					<input type="date" name="date" required>
				</label>

				<label class="field file-field">
					<span>Foto Profil</span>
					<div class="file-row">
						<input id="avatar" type="file" name="avatar" accept="image/png, image/jpeg">
						<span id="fileName" class="file-name">Belum ada file</span>
					</div>
					<div class="preview" id="preview"></div>
				</label>

				<button class="primary-btn" type="submit" name="submit">Simpan</button>
			</form>
		</section>
	</main>

	<script src="signup.js"></script>

</body>
</html>
