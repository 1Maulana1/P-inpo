<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Simulasi database produk
$produk_data = [
    1 => [
        'id' => 1,
        'nama' => 'Samsung Galaxy A33',
        'harga' => 3850000,
        'gambar' => 'https://images.unsplash.com/photo-1511707267537-b85faf00021e?w=400&h=300&fit=crop',
        'stok' => 15,
        'spesifikasi' => [
            ['label' => 'Prosesor', 'value' => 'Snapdragon 778G'],
            ['label' => 'RAM', 'value' => '8GB'],
            ['label' => 'Storage', 'value' => '256GB'],
            ['label' => 'Layar', 'value' => '6.4 inch AMOLED'],
            ['label' => 'Kamera', 'value' => '64MP + 12MP + 5MP + 2MP'],
            ['label' => 'Baterai', 'value' => '5000 mAh'],
            ['label' => 'OS', 'value' => 'Android 12']
        ],
        'deskripsi' => 'Samsung Galaxy A33 adalah smartphone mid-range yang menawarkan performa solid dengan prosesor Snapdragon 778G. Dilengkapi dengan layar AMOLED 6.4 inch yang jernih dan responsif, serta sistem kamera quad yang dapat menghasilkan foto berkualitas tinggi. Baterai 5000mAh yang tahan lama membuat perangkat ini cocok untuk penggunaan sehari-hari. Desain yang elegan dan tahan air (IP67) menambah nilai jual produk ini.'
    ],
    2 => [
        'id' => 2,
        'nama' => 'Casing Samsung A33 Bening',
        'harga' => 38500,
        'gambar' => 'https://images.unsplash.com/photo-1603561596411-07134e71a2a9?w=400&h=300&fit=crop',
        'stok' => 50,
        'spesifikasi' => [
            ['label' => 'Material', 'value' => 'TPU + Polycarbonate'],
            ['label' => 'Tipe', 'value' => 'Pelindung Sudut'],
            ['label' => 'Warna', 'value' => 'Transparan'],
            ['label' => 'Kompatibel', 'value' => 'Samsung Galaxy A33'],
            ['label' => 'Berat', 'value' => '25 gram']
        ],
        'deskripsi' => 'Casing protective untuk Samsung Galaxy A33 dengan desain bening yang mempertahankan estetika asli ponsel. Terbuat dari material TPU berkualitas tinggi dengan lapisan polycarbonate yang kuat, memberikan perlindungan maksimal terhadap jatuh dan benturan. Desain dengan pelindung sudut khusus melindungi layar dan kamera. Akses mudah ke semua port dan tombol.'
    ],
    3 => [
        'id' => 3,
        'nama' => 'Voucher Diskon 50%',
        'harga' => 50000,
        'gambar' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=400&h=300&fit=crop',
        'stok' => 999,
        'spesifikasi' => [
            ['label' => 'Tipe', 'value' => 'Voucher Digital'],
            ['label' => 'Diskon', 'value' => '50%'],
            ['label' => 'Maksimal', 'value' => 'Rp100.000'],
            ['label' => 'Berlaku Untuk', 'value' => 'Semua Produk'],
            ['label' => 'Masa Berlaku', 'value' => '30 hari']
        ],
        'deskripsi' => 'Voucher diskon 50% yang dapat digunakan untuk pembelian produk apapun. Voucher ini berlaku untuk semua kategori produk tanpa minimum pembelian. Gunakan dalam sekali transaksi sebelum masa berlaku berakhir. Satu akun hanya dapat menggunakan satu voucher per transaksi.'
    ],
    4 => [
        'id' => 4,
        'nama' => 'Charger Cepat 65W USB-C',
        'harga' => 189000,
        'gambar' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=400&h=300&fit=crop',
        'stok' => 30,
        'spesifikasi' => [
            ['label' => 'Daya', 'value' => '65W'],
            ['label' => 'Input', 'value' => 'AC 100-240V'],
            ['label' => 'Output', 'value' => 'USB Type-C'],
            ['label' => 'Kabel Termasuk', 'value' => 'Ya (USB-C)'],
            ['label' => 'Sertifikasi', 'value' => 'FCC, CE']
        ],
        'deskripsi' => 'Charger berteknologi fast charging 65W dengan output USB Type-C. Cocok untuk mengisi daya smartphone, tablet, dan laptop dengan cepat dan aman. Dilengkapi dengan teknologi smart charging yang dapat mendeteksi perangkat secara otomatis untuk pengisian optimal. Desain compact dan mudah dibawa kemana-mana.'
    ]
];

// Handle GET request untuk ambil detail produk
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $produk_id = intval($_GET['id']);
    
    if (isset($produk_data[$produk_id])) {
        echo json_encode($produk_data[$produk_id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Produk tidak ditemukan']);
    }
    exit;
}

// Handle POST request untuk simpan notifikasi atau order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Contoh: Simpan notifikasi ke database
    if (isset($input['action']) && $input['action'] === 'add_notification') {
        $response = [
            'status' => 'success',
            'message' => 'Notifikasi berhasil ditambahkan',
            'data' => $input
        ];
        echo json_encode($response);
        exit;
    }
    
    // Contoh: Proses order
    if (isset($input['action']) && $input['action'] === 'process_order') {
        $response = [
            'status' => 'success',
            'message' => 'Pesanan berhasil diproses',
            'order_id' => 'ORD-' . date('YmdHis'),
            'data' => $input
        ];
        echo json_encode($response);
        exit;
    }
}

// Default response - tampilkan semua produk
echo json_encode($produk_data);
?>
