<?php
session_start();

// Handle POST JSON actions for cart (add, update, remove, clear)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    $action = $data['action'] ?? null;
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];
    $cart = &$_SESSION['cart'];

    switch ($action) {
        case 'add':
            $item = $data['item'] ?? null;
            if (!$item || !isset($item['id'])) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Missing item id']);
                exit;
            }
            $found = false;
            foreach ($cart as &$ci) {
                if ((string)$ci['id'] === (string)$item['id']) {
                    $ci['qty'] = (int)($ci['qty'] ?? 0) + (int)($item['qty'] ?? 1);
                    $found = true;
                    break;
                }
            }
            unset($ci);
            if (!$found) {
                $cart[] = [
                    'id' => $item['id'],
                    'name' => $item['name'] ?? '',
                    'price' => (float)($item['price'] ?? 0),
                    'qty' => (int)($item['qty'] ?? 1),
                    'img' => $item['img'] ?? ''
                ];
            }
            break;

        case 'update':
            $id = $data['id'] ?? null;
            $qty = isset($data['qty']) ? (int)$data['qty'] : null;
            if ($id === null || $qty === null) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Missing id or qty']);
                exit;
            }
            foreach ($cart as $k => $ci) {
                if ((string)$ci['id'] === (string)$id) {
                    if ($qty <= 0) {
                        array_splice($cart, $k, 1);
                    } else {
                        $cart[$k]['qty'] = $qty;
                    }
                    break;
                }
            }
            break;

        case 'remove':
            $id = $data['id'] ?? null;
            if ($id === null) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Missing id']);
                exit;
            }
            foreach ($cart as $k => $ci) {
                if ((string)$ci['id'] === (string)$id) {
                    array_splice($cart, $k, 1);
                    break;
                }
            }
            break;

        case 'clear':
            $cart = [];
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
            exit;
    }

    // Compute subtotal
    $subtotal = 0;
    foreach ($cart as $ci) {
        $subtotal += (float)($ci['price'] ?? 0) * (int)($ci['qty'] ?? 1);
    }

    echo json_encode(['status' => 'ok', 'cart' => $cart, 'subtotal' => $subtotal]);
    exit;
}

$cartJson = json_encode($_SESSION['cart'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
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
<?php
// Inject current cart state into JS for keranjang.js
echo '<script>var cart = ' . $cartJson . ';</script>' . PHP_EOL;
?>
<script src="keranjang.js"></script>
</body>
</html>
