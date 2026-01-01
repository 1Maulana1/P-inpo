document.addEventListener('DOMContentLoaded', function() {
    // 1. Sinkronisasi Avatar Sidebar
    const sidebarAvatar = document.getElementById('sidebar-avatar');
    const savedAvatar = localStorage.getItem('userAvatar');
    if (savedAvatar && sidebarAvatar) {
        sidebarAvatar.src = savedAvatar;
    }

    // 2. Logika Modal Alamat
    const modal = document.getElementById('addressModal');
    const btnOpen = document.getElementById('openModal');
    const btnClose = document.getElementById('closeModal');

    if (btnOpen) {
        btnOpen.onclick = () => { modal.style.display = 'flex'; };
    }

    if (btnClose) {
        btnClose.onclick = () => { modal.style.display = 'none'; };
    }

    // Tutup modal jika klik di luar box modal
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});