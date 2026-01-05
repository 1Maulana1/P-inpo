src="https://cdn.jsdelivr.net/npm/sweetalert2@11"

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
