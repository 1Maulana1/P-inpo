# 📬 Sistem Notifikasi Melayang

Sistem notifikasi interaktif yang melayang di sudut kanan atas layar, dengan fitur klik untuk melihat detail produk. Terinspirasi dari UI Shopee dengan desain modern dan responsif.

## 📁 File-file yang Dibuat

```
notifikasi.html         - Struktur HTML halaman notifikasi
notifikasi.css          - Styling dan animasi notifikasi
notifikasi.js           - Logic JavaScript untuk fungsi notifikasi
get_produk.php          - API PHP untuk retrieve data produk
send_notification.php   - Class PHP untuk membuat notifikasi dari server
README_NOTIFIKASI.md    - Dokumentasi ini
```

## 🎯 Fitur Utama

### 1. Notifikasi Melayang
- Muncul otomatis di sudut kanan atas
- Animasi slide in/out yang smooth
- Auto-hide setelah 6 detik
- Dapat ditutup manual dengan tombol close

### 2. Berbagai Tipe Notifikasi
- **Success** (✓) - Pembayaran berhasil, transaksi sukses
- **Warning** (⚠) - Pembatalan, pengembalian dana
- **Info** (ℹ) - Informasi umum
- **Error** (✕) - Error atau kesalahan
- **Promo** (🎁) - Penawaran dan promosi

### 3. Detail Produk Modal
- Klik notifikasi untuk buka detail produk
- Menampilkan gambar, harga, spesifikasi, dan deskripsi
- Tombol "Beli Sekarang" dan "Tambah ke Keranjang"
- Responsive design

### 4. Backend Support
- Class PHP `Notifikasi` untuk generate notifikasi dari server
- API endpoint untuk retrieve data produk
- Mudah diintegrasikan ke sistem existing

## 🚀 Cara Menggunakan

### 1. Setup Dasar
Pastikan semua file sudah di tempatkan di folder yang sama:
```
notifikasi.html
notifikasi.css
notifikasi.js
get_produk.php
send_notification.php
```

### 2. Buka di Browser
```
http://localhost/notifikasi.html
```

Notifikasi akan muncul otomatis saat halaman dimuat, atau klik tombol "Test Notifikasi" untuk menampilkan notifikasi lain.

### 3. Gunakan di Aplikasi Anda

**Menambah Notifikasi via JavaScript:**
```javascript
notifikasiManager.tambahNotifikasi({
    tipe: 'success',
    icon: '✓',
    judul: 'Pembayaran Berhasil',
    subtitle: 'Rp38.500 via ShopeePay',
    pesan: 'Pembayaran sebesar Rp38.500 dengan ShopeePay telah berhasil.',
    amount: 'Rp38.500',
    detail: [
        { label: 'Metode', value: 'ShopeePay' },
        { label: 'Tanggal', value: '18 Des 2025' }
    ],
    produkId: 1,
    produk: {
        id: 1,
        nama: 'Samsung A33',
        harga: 3850000,
        gambar: 'url-gambar',
        stok: 15,
        spesifikasi: [
            { label: 'Prosesor', value: 'Snapdragon 778G' }
        ],
        deskripsi: 'Deskripsi produk...'
    }
});
```

**Menggunakan dari PHP:**
```php
require 'send_notification.php';

$notif = new Notifikasi();
$notif->pembayaranBerhasil(38500, 'ShopeePay', 'ORD-251218');
$data = $notif->getData();

// Return as JSON
header('Content-Type: application/json');
echo json_encode($data);
```

### 4. Integrasi ke Database

File `get_produk.php` saat ini menggunakan data hardcoded. Untuk menggunakan database:

```php
<?php
// Contoh: Ambil data dari database MySQL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $produk_id = intval($_GET['id']);
    
    // Query ke database
    $query = "SELECT * FROM produk WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$produk_id]);
    $produk = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produk) {
        // Format data sesuai struktur yang diharapkan
        $response = [
            'id' => $produk['id'],
            'nama' => $produk['nama'],
            'harga' => $produk['harga'],
            'gambar' => $produk['gambar_url'],
            'stok' => $produk['stok'],
            'spesifikasi' => json_decode($produk['spesifikasi']),
            'deskripsi' => $produk['deskripsi']
        ];
        echo json_encode($response);
    }
}
?>
```

## 📋 Struktur Data

### Notifikasi Object
```javascript
{
    tipe: 'success|warning|info|error|promo',
    icon: '✓',
    judul: 'Judul Notifikasi',
    subtitle: 'Subtitle (opsional)',
    pesan: 'Pesan detail',
    amount: 'Rp38.500 (opsional)',
    detail: [
        { label: 'Label', value: 'Nilai' }
    ],
    produkId: 1,
    produk: { /* data produk */ }
}
```

### Produk Object
```javascript
{
    id: 1,
    nama: 'Nama Produk',
    harga: 3850000,
    gambar: 'https://...',
    stok: 15,
    spesifikasi: [
        { label: 'Prosesor', value: 'Snapdragon 778G' },
        { label: 'RAM', value: '8GB' }
    ],
    deskripsi: 'Deskripsi lengkap produk...'
}
```

## 🎨 Customization

### Ubah Warna Tema
Edit di `notifikasi.css`:
```css
/* Warna default adalah orange (#FF5722) */
/* Ubah di variabel CSS: */
.btn-beli {
    background-color: #FF5722; /* Ubah ke warna pilihan */
}

.notifikasi-item {
    border-left-color: #FF5722; /* Ubah ke warna pilihan */
}
```

### Ubah Posisi Notifikasi
Edit di `notifikasi.css`:
```css
.notifikasi-container {
    top: 80px;      /* Ubah untuk posisi vertikal */
    right: 20px;    /* Ubah untuk posisi horizontal */
    /* Untuk di kiri: left: 20px; */
}
```

### Ubah Durasi Notifikasi
Edit di `notifikasi.js`:
```javascript
// Ubah 6000 (6 detik) ke nilai lain (dalam milliseconds)
setTimeout(() => {
    if (notifikasiItem.parentElement) {
        notifikasiItem.classList.add('removing');
        setTimeout(() => {
            notifikasiItem.remove();
        }, 300);
    }
}, 6000); // 6000ms = 6 detik
```

## 🔗 API Endpoints

### GET /get_produk.php?id=1
Mengambil detail produk berdasarkan ID

**Response:**
```json
{
    "id": 1,
    "nama": "Samsung Galaxy A33",
    "harga": 3850000,
    "gambar": "https://...",
    "stok": 15,
    "spesifikasi": [...],
    "deskripsi": "..."
}
```

### POST /get_produk.php
Menyimpan notifikasi atau order

**Request Body:**
```json
{
    "action": "add_notification|process_order",
    "data": {...}
}
```

### POST /send_notification.php
Generate notifikasi dari server

**Form Data:**
```
type=payment_success|refund|cancellation|promo|error
amount=38500
method=ShopeePay
order_no=ORD-251218
product_id=1 (opsional)
```

## 📱 Responsive Design

Sistem notifikasi sudah responsive:
- **Desktop**: Notifikasi di kanan atas dengan max-width 380px
- **Tablet**: Menyesuaikan dengan ukuran layar
- **Mobile**: Full width dengan padding, muncul di atas semua konten

## 🐛 Troubleshooting

### Notifikasi tidak muncul
- Periksa apakah file CSS dan JS sudah ter-load
- Buka Browser Console (F12) cek error message
- Pastikan JavaScript tidak ter-block

### Detail produk tidak muncul
- Periksa apakah file `get_produk.php` accessible
- Pastikan produk ID valid
- Cek di Browser Console untuk error message

### Gambar tidak muncul
- Pastikan URL gambar valid dan accessible
- Cek CORS settings jika gambar dari domain lain

## 🔐 Security Notes

1. **Sanitize Input**: Selalu sanitize user input sebelum ditampilkan
2. **Validate Data**: Validate semua data dari client di server
3. **Rate Limiting**: Implementasikan rate limiting untuk API endpoints
4. **Authentication**: Pastikan user authenticated sebelum akses data

Contoh sanitize PHP:
```php
$judul = htmlspecialchars($_GET['judul'], ENT_QUOTES);
$pesan = htmlspecialchars($_POST['pesan'], ENT_QUOTES);
```

## 📚 Library & Dependencies

Tidak ada external dependency yang diperlukan!

Sistem ini menggunakan:
- Pure HTML5
- Pure CSS3 (dengan animasi)
- Pure JavaScript (ES6+)
- Pure PHP

Kompatibel dengan:
- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

## 📝 License

Bebas digunakan untuk project komersial maupun personal.

## 💡 Tips & Tricks

1. **Notification Queue**: Notifikasi otomatis ter-queue jika ada banyak notifikasi
2. **Sound Alert**: Tambahkan audio notification dengan `<audio>` tag
3. **Persistence**: Simpan notifikasi di localStorage untuk permanent records
4. **Analytics**: Track notifikasi yang diklik untuk analytics

Contoh menambah audio:
```javascript
const audio = new Audio('notification-sound.mp3');
audio.play();
```

---

**Dibuat**: 18 Desember 2025
**Version**: 1.0.0
