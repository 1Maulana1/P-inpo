<?php
session_start();

// 1. KONEKSI DATABASE
include_once("../checkout/test.php");

// 2. CEK LOGIN
if (!isset($_SESSION['user_id']) && !isset($_SESSION['nama'])) {
    header("Location: ../login/login.php");
    exit();
}

// 3. CEK KERANJANG
if (empty($_SESSION['keranjang'])) {
    header("Location: ../beranda/beranda.php");
    exit();
}

// 4. AMBIL DATA USER
$user_id = $_SESSION['user_id'] ?? 0;
$queryUser = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
$resUser = mysqli_query($mysqli, $queryUser);
$userData = mysqli_fetch_assoc($resUser);

// Variabel default
$dbName = $userData['full_name'] ?? $userData['nama'] ?? '';
$dbPhone = $userData['phone'] ?? '';
$dbAddress = $userData['address'] ?? '';

// 5. HITUNG TOTAL
$totalBelanja = 0;
$ids = implode(',', array_keys($_SESSION['keranjang']));

if (!empty($ids)) {
    $queryProduk = "SELECT * FROM products WHERE product_id IN ($ids)";
    $resultProduk = mysqli_query($mysqli, $queryProduk);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - netofffice</title>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; color:#333; }
        .container { max-width: 1000px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px; }
        
        .checkout-form { flex: 2; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        
        .order-summary { flex: 1; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); height: fit-content; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; color: #555; }
        .total-row { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 2px dashed #eee; font-weight: bold; font-size: 18px; color: #0056b3; }
        
        .btn-bayar { background: #0056b3; color: white; border: none; padding: 15px; width: 100%; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 20px; transition: 0.3s; }
        .btn-bayar:hover { background: #004494; }
        .btn-bayar:disabled { background: #ccc; cursor: not-allowed; }
        
        .back-link { text-decoration: none; color: #666; font-size: 14px; display: block; margin-bottom: 15px; }
        h2 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    
    <!-- KOLOM KIRI: FORMULIR -->
    <div class="checkout-form">
        <a href="../keranjang/keranjang.php" class="back-link">← Kembali ke Keranjang</a>
        <h2>Informasi Pengiriman</h2>
        
        <!-- Tambahkan ID pada form agar bisa diakses JS -->
        <form id="paymentForm" action="proses_bayar.php" method="POST">
            <div class="form-group">
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" value="<?= htmlspecialchars($dbName) ?>" required placeholder="Nama Lengkap">
            </div>

            <div class="form-group">
                <label>Nomor Telepon / WhatsApp</label>
                <input type="text" name="telepon" value="<?= htmlspecialchars($dbPhone) ?>" required placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap Pengiriman</label>
                <textarea name="alamat" rows="4" required placeholder="Nama Jalan, No. Rumah, Kecamatan, Kota..."><?= htmlspecialchars($dbAddress) ?></textarea>
            </div>

            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode_pembayaran" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Transfer BCA">Transfer Bank BCA (Cek Manual)</option>
                    <option value="Transfer Mandiri">Transfer Bank Mandiri</option>
                    <option value="COD">Bayar di Tempat (COD)</option>
                </select>
            </div>

            <input type="hidden" name="total_hidden" id="total_hidden" value="">
            
            <!-- Ubah type button agar tidak submit otomatis, kita handle via JS -->
            <button type="submit" class="btn-bayar" id="btnSubmit">Buat Pesanan Sekarang</button>
        </form>
    </div>

    <!-- KOLOM KANAN: RINGKASAN PRODUK -->
    <div class="order-summary">
        <h2>Ringkasan Pesanan</h2>
        
        <?php 
        if (isset($resultProduk) && mysqli_num_rows($resultProduk) > 0) {
            while($row = mysqli_fetch_assoc($resultProduk)) {
                $qty = $_SESSION['keranjang'][$row['product_id']];
                $subtotal = $row['price'] * $qty;
                $totalBelanja += $subtotal;
        ?>
            <div class="summary-item">
                <span><?= htmlspecialchars(substr($row['name'], 0, 25)) ?>... (x<?= $qty ?>)</span>
                <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
        <?php 
            }
        }
        ?>

        <div class="summary-item">
            <span>Biaya Pengiriman</span>
            <span style="color:green;">Gratis</span>
        </div>

        <div class="total-row">
            <span>Total Bayar</span>
            <span>Rp <?= number_format($totalBelanja, 0, ',', '.') ?></span>
        </div>
    </div>

</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Set total harga
    document.getElementById('total_hidden').value = "<?= $totalBelanja ?>";

    // --- LOGIKA JS UNTUK SUBMIT FORM ---
    const form = document.getElementById('paymentForm');
    const btnSubmit = document.getElementById('btnSubmit');

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman biasa

        // 1. Ubah tombol jadi loading
        btnSubmit.innerHTML = 'Memproses...';
        btnSubmit.disabled = true;

        // 2. Ambil data form
        const formData = new FormData(form);

        // 3. Kirim via Fetch (AJAX)
        fetch('proses_bayar.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            // Cek jika file tidak ditemukan atau error server (500)
            if (!response.ok) {
                throw new Error(`HTTP Error! Status: ${response.status} (File proses_bayar.php mungkin tidak ditemukan)`);
            }
            // Ambil respon teks dulu (jangan langsung JSON) untuk debugging
            return response.text();
        })
        .then(text => {
            try {
                // Coba parsing ke JSON
                const data = JSON.parse(text);

                if (data.status === 'success') {
                    // 4. Tampilkan Popup Sukses
                    Swal.fire({
                        title: 'Pesanan Berhasil!',
                        text: 'Terima kasih, pesanan Anda sedang kami proses.',
                        icon: 'success',
                        confirmButtonText: 'Lihat Detail',
                        confirmButtonColor: '#0056b3'
                    }).then((result) => {
                        window.location.href = 'selesai.php?order_id=' + data.order_id;
                    });
                } else {
                    // Error dari Backend (misal Gagal Insert DB)
                    throw new Error(data.message || 'Terjadi kesalahan sistem.');
                }
            } catch (e) {
                // Jika respon BUKAN JSON (misal error PHP tercetak di layar)
                console.error("Respon Server:", text);
                throw new Error("Respon server tidak valid: " + text.substring(0, 100) + "...");
            }
        })
        .catch(error => {
            // Tampilkan error di popup
            console.error('Error:', error);
            Swal.fire({
                title: 'Gagal!',
                text: error.message,
                icon: 'error'
            });
            // Reset tombol
            btnSubmit.innerHTML = 'Buat Pesanan Sekarang';
            btnSubmit.disabled = false;
        });
    });
</script>

</body>
</html>