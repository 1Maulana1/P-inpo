        // Fungsi Membuat Toast Notifikasi
        function showToast(productName) {
            const container = document.getElementById('toast-container');
            
            // Buat elemen notifikasi baru
            const toast = document.createElement('div');
            toast.className = 'toast';
            
            // Isi HTML notifikasi
            toast.innerHTML = `
                <div class="toast-icon">✅</div>
                <div class="toast-content">
                    <span class="toast-title">Berhasil!</span>
                    <span class="toast-msg"><b>${productName}</b> masuk keranjang.</span>
                </div>
            `;
            
            // Masukkan ke dalam container
            container.appendChild(toast);
            
            // Hapus otomatis setelah 3 detik
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Fungsi Tambah Keranjang
        function addToCart(id, name) {
            // 1. Update Badge Angka Keranjang
            let badge = document.getElementById('cartCount');
            if(badge) {
                let currentCount = parseInt(badge.innerText);
                badge.innerText = currentCount + 1;
            }
            
            // 2. Tampilkan Animasi Toast
            showToast(name);
            
            // 3. Kirim data ke backend (Silent Request) agar masuk Session
            // Pastikan path aksi_keranjang.php sesuai
            fetch('../keranjang/aksi_keranjang.php?act=tambah&id=' + id);
        }