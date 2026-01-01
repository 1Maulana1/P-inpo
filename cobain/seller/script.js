// Javascript sederhana untuk Seller Dashboard

document.addEventListener('DOMContentLoaded', function() {
    console.log("Seller Dashboard Loaded");
    
    // Contoh interaksi sederhana
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('click', function() {
            // Animasi klik sederhana
            this.style.transform = "scale(0.98)";
            setTimeout(() => {
                this.style.transform = "scale(1)";
            }, 100);
        });
    });
});

function confirmLogout() {
    if(confirm("Apakah Anda yakin ingin keluar dari Seller Centre?")) {
        window.location.href = "../profil/profile.php";
    }
}