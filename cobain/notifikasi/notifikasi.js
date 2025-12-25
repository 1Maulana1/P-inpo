// Class untuk mengelola notifikasi
class NotifikasiManager {
    constructor() {
        this.container = document.getElementById('notifikasiContainer');
        this.modal = document.getElementById('modalDetail');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Close modal saat tombol close diklik
        document.querySelector('.close').addEventListener('click', () => {
            this.closeModal();
        });

        // Close modal saat klik di luar modal
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });
    }

    // Tambah notifikasi baru
    tambahNotifikasi(data) {
        const notifikasiItem = document.createElement('div');
        notifikasiItem.className = `notifikasi-item ${data.tipe || 'info'}`;
        notifikasiItem.innerHTML = `
            <div class="notifikasi-header">
                <div class="notifikasi-icon">${data.icon}</div>
                <div class="notifikasi-title-wrapper">
                    <p class="notifikasi-title">${data.judul}</p>
                    <p class="notifikasi-subtitle">${data.subtitle || ''}</p>
                </div>
                <button class="notifikasi-close" onclick="this.parentElement.parentElement.parentElement.remove()">×</button>
            </div>
            <div class="notifikasi-body">
                ${data.amount ? `<div class="notifikasi-amount">${data.amount}</div>` : ''}
                <div>${data.pesan}</div>
                ${data.detail ? `
                    <div class="notifikasi-detail">
                        ${data.detail.map(item => `
                            <div class="notifikasi-detail-row">
                                <span class="notifikasi-detail-label">${item.label}</span>
                                <span class="notifikasi-detail-value">${item.value}</span>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;

        // Event listener untuk klik notifikasi (membuka modal detail produk)
        notifikasiItem.addEventListener('click', (e) => {
            if (e.target.closest('.notifikasi-close')) return;
            if (data.produkId) {
                this.tampilkanDetailProduk(data.produkId, data.produk);
            }
        });

        this.container.appendChild(notifikasiItem);

        // Auto remove notifikasi setelah 6 detik
        setTimeout(() => {
            if (notifikasiItem.parentElement) {
                notifikasiItem.classList.add('removing');
                setTimeout(() => {
                    notifikasiItem.remove();
                }, 300);
            }
        }, 6000);
    }

    // Tampilkan detail produk di modal
    tampilkanDetailProduk(produkId, produkData) {
        const detailDiv = document.getElementById('detailProduk');
        
        if (!produkData) {
            // Jika tidak ada data produk, fetch dari server
            this.fetchProdukDetail(produkId);
        } else {
            // Gunakan data produk yang sudah ada
            this.renderDetailProduk(produkData, detailDiv);
        }

        this.openModal();
    }

    // Fetch detail produk dari server
    fetchProdukDetail(produkId) {
        const detailDiv = document.getElementById('detailProduk');
        detailDiv.innerHTML = '<p style="padding: 20px; text-align: center;">Loading...</p>';

        fetch(`get_produk.php?id=${produkId}`)
            .then(response => response.json())
            .then(data => {
                this.renderDetailProduk(data, detailDiv);
            })
            .catch(error => {
                console.error('Error:', error);
                detailDiv.innerHTML = '<p style="padding: 20px; color: red;">Gagal memuat detail produk</p>';
            });
    }

    // Render detail produk di DOM
    renderDetailProduk(produk, container) {
        container.innerHTML = `
            <div class="detail-produk-header">
                <img src="${produk.gambar}" alt="${produk.nama}" class="detail-produk-image">
                <h2 class="detail-produk-judul">${produk.nama}</h2>
                <div class="detail-produk-harga">${this.formatRupiah(produk.harga)}</div>
                <div style="font-size: 12px; color: #888;">Stok: ${produk.stok} unit</div>
            </div>
            
            <div class="detail-produk-body">
                <div class="detail-produk-section">
                    <div class="detail-produk-section-title">Spesifikasi</div>
                    ${produk.spesifikasi ? produk.spesifikasi.map(spec => `
                        <div class="detail-produk-spesifikasi">
                            <span class="detail-produk-spek-label">${spec.label}</span>
                            <span class="detail-produk-spek-value">${spec.value}</span>
                        </div>
                    `).join('') : '<p style="color: #999;">Tidak ada spesifikasi</p>'}
                </div>

                <div class="detail-produk-section">
                    <div class="detail-produk-section-title">Deskripsi</div>
                    <div class="detail-produk-deskripsi">${produk.deskripsi}</div>
                </div>
            </div>

            <div class="detail-produk-footer">
                <button class="btn-keranjang" onclick="tambahKeKeranjang(${produk.id})">
                    🛒 Keranjang
                </button>
                <button class="btn-beli" onclick="beliSekarang(${produk.id})">
                    Beli Sekarang
                </button>
            </div>
        `;
    }

    // Format rupiah
    formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Open modal
    openModal() {
        this.modal.classList.add('show');
    }

    // Close modal
    closeModal() {
        this.modal.classList.remove('show');
    }
}

// Inisialisasi NotifikasiManager
const notifikasiManager = new NotifikasiManager();

// Fungsi untuk test notifikasi
function testNotifikasi() {
    const tipeNotifikasi = [
        {
            tipe: 'success',
            icon: '✓',
            judul: 'Pembayaran Berhasil',
            subtitle: 'Rp38.500 via ShopeePay',
            pesan: 'Pembayaran sebesar Rp38.500 dengan ShopeePay telah berhasil.',
            amount: 'Rp38.500',
            detail: [
                { label: 'Metode', value: 'ShopeePay' },
                { label: 'Tanggal', value: new Date().toLocaleDateString('id-ID') }
            ],
            produkId: 1,
            produk: {
                id: 1,
                nama: 'Samsung A33',
                harga: 3850000,
                gambar: 'https://images.unsplash.com/photo-1511707267537-b85faf00021e?w=400&h=300&fit=crop',
                stok: 15,
                spesifikasi: [
                    { label: 'Prosesor', value: 'Snapdragon 778G' },
                    { label: 'RAM', value: '8GB' },
                    { label: 'Storage', value: '256GB' },
                    { label: 'Layar', value: '6.4 inch AMOLED' },
                    { label: 'Kamera', value: '64MP + 12MP + 5MP + 2MP' }
                ],
                deskripsi: 'Samsung Galaxy A33 adalah smartphone mid-range yang menawarkan performa solid dengan prosesor Snapdragon 778G. Dilengkapi dengan layar AMOLED 6.4 inch yang jernih dan responsif, serta sistem kamera quad yang dapat menghasilkan foto berkualitas tinggi. Baterai 5000mAh yang tahan lama membuat perangkat ini cocok untuk penggunaan sehari-hari. Desain yang elegan dan tahan air (IP67) menambah nilai jual produk ini.'
            }
        },
        {
            tipe: 'warning',
            icon: '⚠',
            judul: 'Pengajuan Pembatalan Diterima',
            subtitle: 'Nomor pesanan: 251218P4',
            pesan: 'Permintaan pembatalan telah diterima. Uang akan dikembalikan dalam 1 hari kerja.',
            detail: [
                { label: 'Nomor Pesanan', value: '251218P4' },
                { label: 'Dana Dikembalikan', value: 'Rp38.500' },
                { label: 'Waktu Estimasi', value: '1 hari kerja' }
            ],
            produkId: 2,
            produk: {
                id: 2,
                nama: 'Casing Samsung A33 Bening',
                harga: 38500,
                gambar: 'https://images.unsplash.com/photo-1603561596411-07134e71a2a9?w=400&h=300&fit=crop',
                stok: 50,
                spesifikasi: [
                    { label: 'Material', value: 'TPU + Polycarbonate' },
                    { label: 'Tipe', value: 'Pelindung Sudut' },
                    { label: 'Warna', value: 'Transparan' },
                    { label: 'Kompatibel', value: 'Samsung Galaxy A33' }
                ],
                deskripsi: 'Casing protective untuk Samsung Galaxy A33 dengan desain bening yang mempertahankan estetika asli ponsel. Terbuat dari material TPU berkualitas tinggi dengan lapisan polycarbonate yang kuat, memberikan perlindungan maksimal terhadap jatuh dan benturan. Desain dengan pelindung sudut khusus melindungi layar dan kamera. Akses mudah ke semua port dan tombol.'
            }
        },
        {
            tipe: 'info',
            icon: '🎁',
            judul: 'Promo Konten Bebas Biaya',
            subtitle: 'Dapatkan bonus kepoin',
            pesan: 'Biaya promo Affiliate buat keplak bisa 100% ditanggung Shopee! Cek selengkapnya.',
            produkId: 3,
            produk: {
                id: 3,
                nama: 'Voucher Diskon 50%',
                harga: 50000,
                gambar: 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=400&h=300&fit=crop',
                stok: 999,
                spesifikasi: [
                    { label: 'Tipe', value: 'Voucher Digital' },
                    { label: 'Diskon', value: '50%' },
                    { label: 'Maksimal', value: 'Rp100.000' },
                    { label: 'Berlaku Untuk', value: 'Semua Produk' }
                ],
                deskripsi: 'Voucher diskon 50% yang dapat digunakan untuk pembelian produk apapun. Voucher ini berlaku untuk semua kategori produk tanpa minimum pembelian. Gunakan dalam sekali transaksi sebelum masa berlaku berakhir. Satu akun hanya dapat menggunakan satu voucher per transaksi.'
            }
        }
    ];

    const random = tipeNotifikasi[Math.floor(Math.random() * tipeNotifikasi.length)];
    notifikasiManager.tambahNotifikasi(random);
}

// Fungsi tambah ke keranjang
function tambahKeKeranjang(produkId) {
    alert(`Produk ${produkId} ditambahkan ke keranjang!`);
    notifikasiManager.closeModal();
}

// Fungsi beli sekarang
function beliSekarang(produkId) {
    alert(`Melanjutkan pembelian produk ${produkId}...`);
    notifikasiManager.closeModal();
}

// Auto show sample notifikasi saat halaman load
window.addEventListener('load', () => {
    setTimeout(() => {
        testNotifikasi();
    }, 500);
});
