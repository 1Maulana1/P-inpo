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
					<input type="tel" name="telp" placeholder="08xxxxxxxxxx" required>
				</label>

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

    <?php
// Check If form submitted, insert form data into users table.
if(isset($_POST['Submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $document = $_POST['document'];
    $child = $_POST['child'];
// include database connection file
include_once("config.php");
// Insert user data into table
$result = mysqli_query($mysqli, "INSERT INTO
users(name,email,mobile,gender,address,document,child) VALUES('$name','$email','$mobile', '$gender', '$address', '$document', '$child')");
// Show message when user added
echo "User added successfully. <a href='index.php'>View Users</a>";
}
?>

</body>
</html>
