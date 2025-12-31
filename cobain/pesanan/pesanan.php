<?php
session_start();

$ordersFile = __DIR__ . '/orders.json';
if (!file_exists($ordersFile)) {
    file_put_contents($ordersFile, json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Simple API: POST JSON {action: 'create'|'update_status'|'cancel', ...}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    $orders = json_decode(file_get_contents($ordersFile), true) ?: [];
    $action = $data['action'] ?? '';

    if ($action === 'create') {
        $orderId = 'ORD' . time() . rand(100, 999);
        $order = [
            'id' => $orderId,
            'userId' => $_SESSION['user']['id'] ?? null,
            'items' => $data['items'] ?? [],
            'total' => $data['total'] ?? 0,
            'shipping' => $data['shipping'] ?? null,
            'status' => 'pending',
            'created_at' => date('c')
        ];
        $orders[] = $order;
        file_put_contents($ordersFile, json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'ok', 'order' => $order]);
        exit;
    }

    if ($action === 'update_status' || $action === 'cancel') {
        $orderId = $data['orderId'] ?? null;
        $newStatus = $data['status'] ?? ($action === 'cancel' ? 'cancelled' : null);
        if (!$orderId || !$newStatus) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Missing orderId or status']);
            exit;
        }
        $updated = false;
        foreach ($orders as $i => $o) {
            if ($o['id'] === $orderId) {
                $orders[$i]['status'] = $newStatus;
                $orders[$i]['updated_at'] = date('c');
                $updated = true;
                break;
            }
        }
        if ($updated) {
            file_put_contents($ordersFile, json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            echo json_encode(['status' => 'ok']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
        }
        exit;
    }

    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
    exit;
}

// For GET: prepare orders to inject into JS (filter by logged-in user if any)
$allOrders = json_decode(file_get_contents($ordersFile), true) ?: [];
$currentUser = $_SESSION['user'] ?? null;
if ($currentUser) {
    $ordersForUser = array_values(array_filter($allOrders, function($o) use ($currentUser) {
        return isset($o['userId']) && $o['userId'] == $currentUser['id'];
    }));
} else {
    // If not logged in, show all orders (or none); here we'll show none to protect privacy
    $ordersForUser = [];
}
$ordersJson = json_encode($ordersForUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$currentUserJson = json_encode($currentUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="pesanan.css">
    <script src="pesanan.js" defer></script>
</head>

<body class="min-h-screen flex flex-col">

    <header>
        <div class="top-header"> 
            <div class="top-left">
                <span>netofffice · B2B Elektronik Kantor</span>
            </div>

            <div class="top-right">
                <a href="../login/signup/signup.html">Daftar</a>
                <span>|</span>
                <a href="../login/login.html">Log In</a>
            </div>
        </div>

        <div class="main-header">
            <div class="logo"><a class="home" href="../beranda/beranda.html">netofffice</a></div>
            <div class="search-box"> 
                <input type="text" placeholder="Cari elektronik kantor">
                <button> 🔍 </button>
            </div>
            <div class="cart-icon">🛒</div>
        </div>
    </header>

    <div class="content-wrapper">
        <aside class="sidebar">
            <nav class="sidebar-nav">
            </nav>
        </aside>

        <div class="main-content">
            <div class="tabs-wrapper">
                <a class="back-home" href="../beranda/beranda.html">← Kembali</a>
                <div id="tabs-container" class="tabs-container hide-scroll"> </div>
            </div>
            <div id="orders-list" class="orders-list"> </div>
        </div>
    </div>

</body>
</html>

    <?php
    // Inject orders and currentUser for pesanan.js
    echo "<script>var orders = $ordersJson; var currentUser = $currentUserJson;</script>\n";
    ?>
