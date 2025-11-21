-- ⬇️ 文字化け防止のおまじない (これがDocker設定ではなくSQLコマンドです)
SET NAMES utf8mb4;

-- 1. ユーザー (users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    name VARCHAR(255) NOT NULL COMMENT '氏名',
    email VARCHAR(255) UNIQUE NOT NULL COMMENT 'メールアドレス',
    password VARCHAR(255) NOT NULL COMMENT 'パスワード',
    address VARCHAR(255) COMMENT '住所'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. 商品マスタ (products)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    name VARCHAR(255) NOT NULL COMMENT '商品名',
    description TEXT COMMENT '商品説明',
    price INT NOT NULL COMMENT '価格',
    stock INT NOT NULL COMMENT '在庫数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. 注文ヘッダ (orders)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    user_id INT NOT NULL COMMENT '購入ユーザーID',
    total_price INT NOT NULL COMMENT '合計金額',
    shipping_address VARCHAR(255) COMMENT '配送先住所',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '注文日時',
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. 注文明細 (order_items)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    order_id INT NOT NULL COMMENT '注文ID',
    product_id INT NOT NULL COMMENT '商品ID',
    quantity INT NOT NULL COMMENT '数量',
    price INT NOT NULL COMMENT '購入時の価格',
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- テストデータ投入
INSERT INTO users (name, email, password, address) VALUES
('テスト 太郎', 'test@example.com', 'password123', '東京都新宿区1-1-1');

INSERT INTO products (name, description, price, stock) VALUES
('ノートパソコン', '高性能な業務PCです。', 120000, 10),
('ワイヤレスマウス', '手に馴染むデザイン。', 3000, 50),
('モニター 24インチ', '目に優しいディスプレイ。', 15000, 20);