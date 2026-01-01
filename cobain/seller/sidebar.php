<!-- sidebar.php -->
<aside class="sidebar">
    <div class="nav-group">
        <a href="index.php?page=dashboard" class="nav-item <?php echo ($_GET['page']=='dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-gauge-high" style="width:24px;"></i> Dashboard
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-group-title">Produk</div>
        <a href="index.php?page=produk" class="nav-item <?php echo ($_GET['page']=='produk') ? 'active' : ''; ?>">
            <i class="fa-solid fa-box" style="width:24px;"></i> Produk Saya
        </a>
        <a href="tambah_produk.php" class="nav-item">
            <i class="fa-solid fa-plus" style="width:24px;"></i> Tambah Produk
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-group-title">Akun</div>
        <a href="index.php?page=settings" class="nav-item <?php echo ($_GET['page']=='settings') ? 'active' : ''; ?>">
            <i class="fa-solid fa-shop" style="width:24px;"></i> Profil Toko
        </a>
        <a href="logout.php" class="nav-item">
            <i class="fa-solid fa-right-from-bracket" style="width:24px;"></i> Keluar
        </a>
    </div>
</aside>