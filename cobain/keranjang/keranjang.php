<!DOCTYPE html>
<html lang="id">
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="keranjang.css">
</head>
<body>

    <header>
        <div class="top-header"> 
            <div class="top-left">
                <a href="#">Seller Centre</a>
                <span> | </span>
                <a href="#">Ikuti kami di</a>
                <a href="https://www.instagram.com/informatics.uii/"> @ </a>
            </div>

            <div class="top-right">
                <a href="#"> username </a>
            </div>
        </div>

        <div class="main-header">
            <div class="logo"> LOGO SEWS</div>
            <div class="search-box"> 
                <input type="text" placeholder="cari produk">
                <button> Q </button>
            </div>
            <div class="cart-icon"> CART</div>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <section class="cart-section">
                <h2>Keranjang Item (<span id="total-item"></span>)</h2>
                <div id="cart-items-container"></div>

                <div class="cart-footer">
                    <button class="btn-belanja" onclick=""> 
                        <- lanjutkan belanja
                    </button>
                    <p class="subtotal"> Subtotal: <span id="cart-subtotal"> Rp 0</span> </p>
                </div>
            </section>

            <aside class="summary">
                <h2>Ringkasan Pesanan</h2>
                
                <div class="summary-row">
                    <span> Subtotal (<span id="summary-item-count"> 0 </span> Items)</span>
                    <span id="summary-subtotal"> Rp 0</span>
                </div>

                <div class="summary-row">
                    <span> Biaya pengiriman </span>
                    <span id="shipping-cost">Rp 0</span>
                </div>

                <div class="summary-total">
                    <span> Total </span>
                    <span id="order-total">Rp 0</span>
                </div>

                <button class="btn-bayar"> Pembayaran </button>
            </aside>
        </div>
    </div>
<script src="keranjang.js"></script>
</body>
</html>
