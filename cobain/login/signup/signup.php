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

    // ===== UPLOAD AVATAR =====
    $avatar = "";
    if (!empty($_FILES['avatar']['name'])) {
        $folder = "uploads/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $avatar = time() . "_" . $_FILES['avatar']['name'];
        move_uploaded_file($_FILES['avatar']['tmp_name'], $folder . $avatar);
    }

    // ===== CEK EMAIL =====
    $cek = mysqli_query($mysqli, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "❌ Email sudah terdaftar";
        exit;
    }

    // ===== INSERT DATA =====
    $query = "
        INSERT INTO users
        (nama,email,phone,password_hash,gender,date,alamat,avatar)
        VALUES
        ('$nama','$email','$phone','$password_hash','$gender','$date','$alamat','$avatar')
    ";

    if (mysqli_query($mysqli, $query)) {
        echo "✅ Registrasi berhasil";
        // header("Location: login.php");
    } else {
        echo "❌ Gagal menyimpan data: " . mysqli_error($mysqli);
    }
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
            <form class="form" action="" method="post" enctype="multipart/form-data">
                
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
                    <input type="tel" name="phone" placeholder="08xxxxxxxxxx" required>
                </label>

                <label class="field">
                    <span>Tanggal Lahir</span>
                    <input type="date" name="date" required>
                </label>

                <label class="field">
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>

                <label class="field">
                    <span>Gender</span>
                    <select name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </label>


                <label class="field">
                    <span>Alamat</span>
                    <textarea name="alamat" id="alamat" placeholder="Alamat lengkap"></textarea>
                </label>

                <label class="field file-field">
                    <span>Foto Profil</span>
                    <div class="file-row">
                        <input id="avatar" type="file" name="avatar" accept="image/png, image/jpeg">
                        <span id="fileName" class="file-name">Belum ada file</span>
                    </div>
                    <div class="preview" id="preview"></div>
                </label>

                <label class="field"> 
                    <a href="/cobain/login/login.php">Sudah punya akun? Login di sini</a>
                </label>

                <button class="primary-btn" type="submit" name="Submit">Simpan</button>
            </form>
        </section>
    </main>
    <script src="signup.js"></script>

</body>
</html>