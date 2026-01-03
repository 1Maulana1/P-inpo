// ================= FORM VALIDATION & SUBMISSION =================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('buatTokoForm');
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
});

// Validate all form fields
function validateForm() {
    const namaToko = document.getElementById('namaToko').value.trim();
    const deskripsiToko = document.getElementById('deskripsiToko').value.trim();
    const kategoriToko = document.getElementById('kategoriToko').value;
    const emailToko = document.getElementById('emailToko').value.trim();
    const teleponToko = document.getElementById('teleponToko').value.trim();
    const alamatToko = document.getElementById('alamatToko').value.trim();
    const kota = document.getElementById('kota').value.trim();
    const provinsi = document.getElementById('provinsi').value.trim();
    const kodePos = document.getElementById('kodePos').value.trim();
    const namaBank = document.getElementById('namaBank').value;
    const nomorRekening = document.getElementById('nomorRekening').value.trim();
    const namaPemilikRekening = document.getElementById('namaPemilikRekening').value.trim();
    const agree = document.getElementById('agree').checked;
    const agreeData = document.getElementById('agreeData').checked;

    // Validate nama toko
    if (namaToko.length < 3) {
        showError('Nama toko minimal 3 karakter');
        return false;
    }

    // Validate deskripsi
    if (deskripsiToko.length < 50) {
        showError('Deskripsi toko minimal 50 karakter');
        return false;
    }

    // Validate kategori
    if (!kategoriToko) {
        showError('Pilih kategori produk utama');
        return false;
    }

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailToko)) {
        showError('Format email tidak valid');
        return false;
    }

    // Validate phone
    const phoneRegex = /^[0-9]{10,13}$/;
    if (!phoneRegex.test(teleponToko)) {
        showError('Nomor telepon harus 10-13 digit angka');
        return false;
    }

    // Validate alamat
    if (alamatToko.length < 10) {
        showError('Alamat terlalu pendek');
        return false;
    }

    // Validate kota & provinsi
    if (kota.length < 3 || provinsi.length < 3) {
        showError('Kota dan provinsi harus diisi dengan benar');
        return false;
    }

    // Validate kode pos
    if (kodePos.length !== 5 || isNaN(kodePos)) {
        showError('Kode pos harus 5 digit angka');
        return false;
    }

    // Validate bank
    if (!namaBank) {
        showError('Pilih nama bank');
        return false;
    }

    // Validate nomor rekening
    if (nomorRekening.length < 8 || isNaN(nomorRekening)) {
        showError('Nomor rekening tidak valid');
        return false;
    }

    // Validate nama pemilik rekening
    if (namaPemilikRekening.length < 3) {
        showError('Nama pemilik rekening harus diisi');
        return false;
    }

    // Validate checkboxes
    if (!agree || !agreeData) {
        showError('Anda harus menyetujui syarat & ketentuan');
        return false;
    }

    return true;
}

// Submit form data
function submitForm() {
    const formData = {
        namaToko: document.getElementById('namaToko').value.trim(),
        deskripsiToko: document.getElementById('deskripsiToko').value.trim(),
        kategoriToko: document.getElementById('kategoriToko').value,
        emailToko: document.getElementById('emailToko').value.trim(),
        teleponToko: document.getElementById('teleponToko').value.trim(),
        alamatToko: document.getElementById('alamatToko').value.trim(),
        kota: document.getElementById('kota').value.trim(),
        provinsi: document.getElementById('provinsi').value.trim(),
        kodePos: document.getElementById('kodePos').value.trim(),
        namaBank: document.getElementById('namaBank').value,
        nomorRekening: document.getElementById('nomorRekening').value.trim(),
        namaPemilikRekening: document.getElementById('namaPemilikRekening').value.trim(),
        createdAt: new Date().toISOString()
    };

    // Save to localStorage (simulation)
    const existingShops = JSON.parse(localStorage.getItem('userShops') || '[]');
    const shopId = 'SHOP-' + Date.now();
    
    formData.id = shopId;
    formData.status = 'active';
    existingShops.push(formData);
    
    localStorage.setItem('userShops', JSON.stringify(existingShops));
    localStorage.setItem('currentShopId', shopId);

    // Show success modal
    showSuccessModal();
}

// Show error message
function showError(message) {
    alert('❌ ' + message);
}

// Show success modal
function showSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.add('active');
    
    // Auto redirect after 3 seconds
    setTimeout(() => {
        redirectToDashboard();
    }, 3000);
}

// Redirect to seller dashboard
function redirectToDashboard() {
    window.location.href = '../seller/index.php';
}

// Real-time validation helpers
document.addEventListener('DOMContentLoaded', function() {
    // Limit kode pos to 5 digits
    const kodePosInput = document.getElementById('kodePos');
    if (kodePosInput) {
        kodePosInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 5);
        });
    }

    // Format phone number
    const teleponInput = document.getElementById('teleponToko');
    if (teleponInput) {
        teleponInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 13);
        });
    }

    // Format rekening number
    const rekeningInput = document.getElementById('nomorRekening');
    if (rekeningInput) {
        rekeningInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Character counter for description
    const deskripsiInput = document.getElementById('deskripsiToko');
    if (deskripsiInput) {
        const small = deskripsiInput.nextElementSibling;
        deskripsiInput.addEventListener('input', function() {
            const length = this.value.trim().length;
            if (small) {
                if (length < 50) {
                    small.textContent = `Minimal 50 karakter (${length}/50)`;
                    small.style.color = '#ef4444';
                } else {
                    small.textContent = `${length} karakter`;
                    small.style.color = '#10b981';
                }
            }
        });
    }
});

// Close modal on background click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('successModal');
    if (e.target === modal) {
        modal.classList.remove('active');
    }
});
