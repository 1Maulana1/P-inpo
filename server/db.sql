-- ===== DDL: tabel utama =====

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  phone VARCHAR(32) UNIQUE,
  email VARCHAR(255) UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(255) DEFAULT '',
  shop_name VARCHAR(255) DEFAULT '',
  gender ENUM('male','female','other','') DEFAULT '',
  birth DATE DEFAULT NULL,
  avatar VARCHAR(512) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE stores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  rating DECIMAL(3,2) DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  store_id INT,
  name VARCHAR(255) NOT NULL,
  `desc` TEXT,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  img VARCHAR(512),
  active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE SET NULL
);

CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  label VARCHAR(100),
  receiver VARCHAR(255),
  phone VARCHAR(50),
  street TEXT,
  city VARCHAR(100),
  postal_code VARCHAR(20),
  province VARCHAR(100),
  is_default TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bank_accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  bank_name VARCHAR(128),
  account_number VARCHAR(64),
  account_name VARCHAR(255),
  is_default TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_no VARCHAR(64) UNIQUE,
  user_id INT,
  total DECIMAL(12,2) NOT NULL,
  shipping_method VARCHAR(100),
  payment_method VARCHAR(100),
  shipping_address_id INT,
  status VARCHAR(50) DEFAULT 'pending',
  note TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (shipping_address_id) REFERENCES addresses(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT,
  store_id INT,
  name VARCHAR(255),
  price DECIMAL(12,2),
  qty INT DEFAULT 1,
  subtotal DECIMAL(12,2),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(255),
  body TEXT,
  data JSON,
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE push_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  token VARCHAR(512),
  platform VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  token VARCHAR(128) UNIQUE,
  expires_at DATETIME,
  used_at DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Optional: persistent cart (if you prefer DB vs session)
CREATE TABLE carts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  session_id VARCHAR(128),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cart_id INT NOT NULL,
  product_id INT,
  name VARCHAR(255),
  price DECIMAL(12,2),
  qty INT DEFAULT 1,
  subtotal DECIMAL(12,2),
  FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE
);

-- ===== Queries per PHP file =====

-- beranda/beranda.php (produk & toko)
SELECT id, name, price, img, `desc`, store_id
FROM products
WHERE active = 1
ORDER BY created_at DESC
LIMIT 500;

SELECT id, name, rating, active
FROM stores
WHERE active = 1;

-- checkout/checkoout.php (buat order)
INSERT INTO orders (order_no, user_id, total, shipping_method, payment_method, shipping_address_id, note)
VALUES (?, ?, ?, ?, ?, ?, ?);

INSERT INTO order_items (order_id, product_id, store_id, name, price, qty, subtotal)
VALUES (?, ?, ?, ?, ?, ?, ?);

-- Ambil alamat user untuk checkout
SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC;

-- keranjang/keranjang.php (jika disimpan di DB)
-- buat/ambil cart
SELECT id FROM carts WHERE user_id = ? LIMIT 1;
INSERT INTO carts (user_id, session_id) VALUES (?, ?);

-- tambahkan/update item keranjang
INSERT INTO cart_items (cart_id, product_id, name, price, qty, subtotal) VALUES (?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty), subtotal = subtotal + VALUES(subtotal);

DELETE FROM cart_items WHERE cart_id = ? AND id = ?;
DELETE FROM cart_items WHERE cart_id = ?;
SELECT * FROM cart_items WHERE cart_id = ?;

-- login/login.php & signup/signup.php
-- login: ambil user by username or phone
SELECT id, username, phone, email, password_hash, name
FROM users
WHERE username = ? OR phone = ?
LIMIT 1;

-- signup: insert user (hash password di PHP)
INSERT INTO users (username, phone, email, password_hash, name) VALUES (?, ?, ?, ?, ?);

-- lupa password (lupapw.php)
INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?);
SELECT pr.id, pr.user_id FROM password_resets pr WHERE pr.token = ? AND pr.expires_at > NOW() AND pr.used_at IS NULL;
UPDATE password_resets SET used_at = NOW() WHERE token = ?;

-- notifikasi/get_produk.php (ambil produk kategori/keyword)
SELECT id, name, price, img, `desc` FROM products WHERE active = 1 AND (name LIKE ? OR `desc` LIKE ?) LIMIT 100;

-- notifikasi/send_notification.php (catat notifikasi & ambil token)
SELECT token FROM push_tokens WHERE user_id = ?;
INSERT INTO notifications (user_id, title, body, data) VALUES (?, ?, ?, ?);

-- pesanan/pesanan.php (CRUD pesanan)
-- buat pesanan (sama seperti checkout)
INSERT INTO orders (order_no, user_id, total, shipping_method, payment_method, shipping_address_id, note) VALUES (?, ?, ?, ?, ?, ?, ?);
-- ambil pesanan user
SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC;
-- ambil item pesanan
SELECT * FROM order_items WHERE order_id = ?;
-- update status
UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?;

-- profil/profil.php (ambil & update profil, alamat, bank)
SELECT id, username, phone, email, name, shop_name, gender, birth, avatar FROM users WHERE id = ?;

UPDATE users
SET name = COALESCE(NULLIF(?,''), name),
    email = COALESCE(NULLIF(?,''), email),
    phone = COALESCE(NULLIF(?,''), phone),
    shop_name = COALESCE(NULLIF(?,''), shop_name),
    gender = COALESCE(NULLIF(?,''), gender),
    birth = COALESCE(?, birth),
    avatar = COALESCE(NULLIF(?,''), avatar),
    updated_at = NOW()
WHERE id = ?;

-- addresses (profil/alamat)
INSERT INTO addresses (user_id, label, receiver, phone, street, city, postal_code, province, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
UPDATE addresses SET label=?, receiver=?, phone=?, street=?, city=?, postal_code=?, province=?, is_default=? WHERE id=? AND user_id=?;
DELETE FROM addresses WHERE id = ? AND user_id = ?;
SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC;

-- bank (profil/bank)
INSERT INTO bank_accounts (user_id, bank_name, account_number, account_name, is_default) VALUES (?, ?, ?, ?, ?);
UPDATE bank_accounts SET bank_name=?, account_number=?, account_name=?, is_default=? WHERE id=? AND user_id=?;
SELECT * FROM bank_accounts WHERE user_id = ?;

-- tambahan util: ambil produk detail
SELECT p.*, s.name AS store_name FROM products p LEFT JOIN stores s ON p.store_id = s.id WHERE p.id = ?;

-- indeks yang direkomendasikan
CREATE INDEX idx_products_active_created ON products(active, created_at);
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);