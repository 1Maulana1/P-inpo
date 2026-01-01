<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "shopee_clone_db"; // Pastikan database ini sudah dibuat

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Error: " . mysqli_connect_error());
}
?>
