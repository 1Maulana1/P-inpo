document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil elemen sidebar avatar di halaman bank
    const sidebarAvatar = document.getElementById('sidebar-avatar');
    
    // 2. Ambil data gambar dari storage
    const savedAvatar = localStorage.getItem('userAvatar');

    // 3. Terapkan jika data ditemukan
    if (savedAvatar && sidebarAvatar) {
        sidebarAvatar.src = savedAvatar;
    }

    // 4. Logika tombol tambah kartu
    const btnAddCard = document.querySelector('.btn-add-card');
    if (btnAddCard) {
        btnAddCard.addEventListener('click', function() {
            alert('Fitur tambah kartu akan segera hadir!');
        });
    }
});