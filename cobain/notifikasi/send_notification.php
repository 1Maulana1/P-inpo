<?php
/**
 * File: send_notification.php
 * Fungsi untuk mengirim notifikasi ke client
 * 
 * Cara penggunaan:
 * $notif = new Notifikasi();
 * $notif->pembayaranBerhasil($amount, $method);
 * $notif->send();
 */

class Notifikasi {
    private $data = [];
    private $tipe = 'info';
    private $icon = 'ℹ';

    /**
     * Notifikasi pembayaran berhasil
     */
    public function pembayaranBerhasil($amount, $method = 'ShopeePay', $orderNo = '12345') {
        $this->tipe = 'success';
        $this->icon = '✓';
        $this->data = [
            'tipe' => $this->tipe,
            'icon' => $this->icon,
            'judul' => 'Pembayaran Berhasil',
            'subtitle' => 'Rp' . number_format($amount, 0, ',', '.') . ' via ' . $method,
            'pesan' => 'Pembayaran sebesar Rp' . number_format($amount, 0, ',', '.') . ' dengan ' . $method . ' telah berhasil.',
            'amount' => 'Rp' . number_format($amount, 0, ',', '.'),
            'detail' => [
                ['label' => 'Metode Pembayaran', 'value' => $method],
                ['label' => 'Nomor Pesanan', 'value' => $orderNo],
                ['label' => 'Tanggal', 'value' => date('d M Y, H:i')]
            ]
        ];
        return $this;
    }

    /**
     * Notifikasi pengembalian dana
     */
    public function pengembalianDana($amount, $alasan = 'Pembatalan Pesanan', $noResi = 'RMA-123456') {
        $this->tipe = 'info';
        $this->icon = '↩';
        $this->data = [
            'tipe' => $this->tipe,
            'icon' => $this->icon,
            'judul' => 'Pengembalian Dana',
            'subtitle' => 'Rp' . number_format($amount, 0, ',', '.') . ' - ' . $alasan,
            'pesan' => 'Pengembalian dana sebesar Rp' . number_format($amount, 0, ',', '.') . ' sedang diproses.',
            'amount' => 'Rp' . number_format($amount, 0, ',', '.'),
            'detail' => [
                ['label' => 'Alasan', 'value' => $alasan],
                ['label' => 'No. RMA', 'value' => $noResi],
                ['label' => 'Estimasi', 'value' => '1-3 hari kerja']
            ]
        ];
        return $this;
    }

    /**
     * Notifikasi pengajuan pembatalan
     */
    public function pengajuanPembatalan($noOrder = 'ORD-123456', $amount = 0, $status = 'Diterima') {
        $this->tipe = 'warning';
        $this->icon = '⚠';
        $this->data = [
            'tipe' => $this->tipe,
            'icon' => $this->icon,
            'judul' => 'Pengajuan Pembatalan ' . $status,
            'subtitle' => 'Nomor pesanan: ' . $noOrder,
            'pesan' => 'Permintaan pembatalan telah ' . strtolower($status) . '. Uang akan dikembalikan dalam 1 hari kerja.',
            'detail' => [
                ['label' => 'Nomor Pesanan', 'value' => $noOrder],
                ['label' => 'Dana Dikembalikan', 'value' => $amount > 0 ? 'Rp' . number_format($amount, 0, ',', '.') : '-'],
                ['label' => 'Status', 'value' => $status],
                ['label' => 'Waktu Estimasi', 'value' => '1 hari kerja']
            ]
        ];
        return $this;
    }

    /**
     * Notifikasi promo/penawaran
     */
    public function promo($judul, $pesan, $icon = '🎁') {
        $this->tipe = 'promo';
        $this->icon = $icon;
        $this->data = [
            'tipe' => $this->tipe,
            'icon' => $this->icon,
            'judul' => $judul,
            'pesan' => $pesan,
            'detail' => [
                ['label' => 'Tanggal', 'value' => date('d M Y')],
                ['label' => 'Status', 'value' => 'Aktif']
            ]
        ];
        return $this;
    }

    /**
     * Notifikasi error
     */
    public function error($judul, $pesan) {
        $this->tipe = 'error';
        $this->icon = '✕';
        $this->data = [
            'tipe' => $this->tipe,
            'icon' => $this->icon,
            'judul' => $judul,
            'pesan' => $pesan
        ];
        return $this;
    }

    /**
     * Custom notifikasi
     */
    public function custom($tipe, $icon, $judul, $pesan, $detail = []) {
        $this->tipe = $tipe;
        $this->icon = $icon;
        $this->data = [
            'tipe' => $tipe,
            'icon' => $icon,
            'judul' => $judul,
            'pesan' => $pesan,
            'detail' => $detail
        ];
        return $this;
    }

    /**
     * Tambah produk ke notifikasi (untuk klik dan buka detail)
     */
    public function denganProduk($produkId, $produkData = null) {
        $this->data['produkId'] = $produkId;
        if ($produkData) {
            $this->data['produk'] = $produkData;
        }
        return $this;
    }

    /**
     * Send notifikasi sebagai JSON
     */
    public function send() {
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }

    /**
     * Get data notifikasi sebagai array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Get data notifikasi sebagai JSON string
     */
    public function getJSON() {
        return json_encode($this->data);
    }
}

// Contoh penggunaan (uncomment untuk testing):
/*
// Notifikasi pembayaran berhasil
$notif = new Notifikasi();
$notif->pembayaranBerhasil(38500, 'ShopeePay', 'ORD-251218')
      ->send();
*/

// Handle API request jika file ini diakses langsung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $notif = new Notifikasi();
    
    switch ($_POST['type']) {
        case 'payment_success':
            $notif->pembayaranBerhasil(
                isset($_POST['amount']) ? $_POST['amount'] : 0,
                isset($_POST['method']) ? $_POST['method'] : 'ShopeePay',
                isset($_POST['order_no']) ? $_POST['order_no'] : 'ORD-123456'
            );
            break;
        
        case 'refund':
            $notif->pengembalianDana(
                isset($_POST['amount']) ? $_POST['amount'] : 0,
                isset($_POST['reason']) ? $_POST['reason'] : 'Pembatalan Pesanan',
                isset($_POST['rma']) ? $_POST['rma'] : 'RMA-123456'
            );
            break;
        
        case 'cancellation':
            $notif->pengajuanPembatalan(
                isset($_POST['order_no']) ? $_POST['order_no'] : 'ORD-123456',
                isset($_POST['amount']) ? $_POST['amount'] : 0,
                isset($_POST['status']) ? $_POST['status'] : 'Diterima'
            );
            break;
        
        case 'promo':
            $notif->promo(
                isset($_POST['title']) ? $_POST['title'] : 'Promo Spesial',
                isset($_POST['message']) ? $_POST['message'] : 'Jangan lewatkan penawaran menarik ini!',
                isset($_POST['icon']) ? $_POST['icon'] : '🎁'
            );
            break;
        
        case 'error':
            $notif->error(
                isset($_POST['title']) ? $_POST['title'] : 'Terjadi Kesalahan',
                isset($_POST['message']) ? $_POST['message'] : 'Silakan coba lagi.'
            );
            break;
        
        default:
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['error' => 'Invalid notification type']);
            exit;
    }
    
    if (isset($_POST['product_id'])) {
        $notif->denganProduk($_POST['product_id']);
    }
    
    header('Content-Type: application/json');
    echo json_encode($notif->getData());
    exit;
}
?>
