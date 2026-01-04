<?php
session_start();
$order_id = $_GET['order_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - netofffice</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f4f6f8; }
        .card { background: white; max-width: 500px; margin: 0 auto; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #28a745; margin-bottom: 10px; }
        p { color: #666; line-height: 1.6; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 25px; background: #0056b3; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .order-id { font-size: 20px; font-weight: bold; color: #333; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div style="font-size: 60px;">✅</div>
        <h1>Pesanan Berhasil!</h1>
        <p>Terima kasih telah berbelanja di netofffice.</p>
        
        <div class="order-id">ID Pesanan: #<?= htmlspecialchars($order_id) ?></div>
        
        <p>Silakan lakukan pembayaran sesuai metode yang dipilih.<br>
        Admin kami akan segera memproses pesanan Anda.</p>
        
        <a href="../beranda/beranda.php" class="btn">Kembali Belanja</a>
        <br><br>
        <a href="../pesanan/pesanan.php" style="color:#0056b3; font-size:14px;">Lihat Riwayat Pesanan</a>
    </div>
</body>
</html>