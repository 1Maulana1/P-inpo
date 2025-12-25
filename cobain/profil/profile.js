// Tunggu sampai halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {

    // 1. Logika Modal "Berhasil diperbarui"
    const btnSave = document.querySelector('.btn-save');
    const modal = document.getElementById('successModal');
    if (btnSave && modal) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault(); 
            modal.style.display = 'flex';
            setTimeout(() => { modal.style.display = 'none'; }, 2000);
        });
    }

    // 2. Logika Ganti Foto Profil
    const fileInput = document.getElementById('file-input');
    const displayAvatar = document.getElementById('display-avatar');
    if (fileInput && displayAvatar) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validasi ukuran (maks 1MB)
                if (file.size > 1048576) {
                    alert("Ukuran file maks. 1 MB"); 
                    this.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) { displayAvatar.src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. Mengisi Dropdown Tanggal (1-31)
    const daySelect = document.getElementById('day');
    if (daySelect) {
        for (let i = 1; i <= 31; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            daySelect.appendChild(option);
        }
    }

    // 4. Mengisi Dropdown Tahun (2025 - 1920)
    const yearSelect = document.getElementById('year');
    if (yearSelect) {
        const currentYear = new Date().getFullYear();
        for (let i = currentYear; i >= 1920; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            yearSelect.appendChild(option);
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // 1. Logika untuk Modal
    const btnSave = document.querySelector('.btn-save');
    const modal = document.getElementById('successModal');
    if (btnSave && modal) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
            setTimeout(() => { modal.style.display = 'none'; }, 2000);
        });
    }

    // 2. Logika untuk Dropdown Tanggal & Tahun (Kode yang sudah Anda buat)
    // ... isi kode pengisian dropdown Anda di sini ...

    // 3. Logika Ganti Foto (Pindahkan ke sini agar tidak merah)
    const fileInput = document.getElementById('file-input');
    const displayAvatar = document.getElementById('display-avatar');
    const sidebarAvatar = document.getElementById('sidebar-avatar');

    if (fileInput && displayAvatar) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validasi ukuran 1MB
                if (file.size > 1048576) {
                    alert("Ukuran file maks. 1 MB");
                    this.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    displayAvatar.src = e.target.result;
                    if (sidebarAvatar) {
                        sidebarAvatar.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}); // Penutup akhir DOMContentLoaded

document.addEventListener('DOMContentLoaded', function() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');

    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            // Hapus class 'active' dari semua menu
            sidebarItems.forEach(i => i.classList.remove('active'));
            
            // Tambahkan class 'active' ke menu yang baru saja diklik
            this.classList.add('active');
        });
    });
});

// ... di dalam event listener fileInput.change ...
const reader = new FileReader();
reader.onload = function(e) {
    const imageData = e.target.result;
    
    // 1. Ganti tampilan di layar saat ini
    displayAvatar.src = imageData;
    if (sidebarAvatar) {
        sidebarAvatar.src = imageData;
    }
    
    // 2. SIMPAN KE STORAGE agar bisa dipanggil halaman lain
    localStorage.setItem('userAvatar', imageData);
};
reader.readAsDataURL(file);

// Di dalam file js/profile.js pada bagian reader.onload
reader.onload = function(e) {
    const imageData = e.target.result;
    
    // Ganti gambar di halaman saat ini
    displayAvatar.src = imageData;
    if (sidebarAvatar) {
        sidebarAvatar.src = imageData;
    }
    
    // SIMPAN KE LOCALSTORAGE
    localStorage.setItem('userAvatar', imageData);
};