<?php
session_start();

$is_cart_transfer = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_json']);
if ($is_cart_transfer) {
    $incoming_cart = json_decode($_POST['cart_json'], true);
    $normalized_cart = [];
    if (is_array($incoming_cart)) {
        foreach ($incoming_cart as $item) {
            if (!isset($item['id'], $item['quantity'])) {
                continue;
            }
            $product_id = (int) $item['id'];
            $quantity = max(1, (int) $item['quantity']);
            if ($product_id <= 0) {
                continue;
            }
            $normalized_cart[$product_id] = ($normalized_cart[$product_id] ?? 0) + $quantity;
        }
    }

    if (!empty($normalized_cart)) {
        $current_cart = $_SESSION['cart'] ?? [];
        foreach ($normalized_cart as $product_id => $quantity) {
            $current_cart[$product_id] = ($current_cart[$product_id] ?? 0) + $quantity;
        }
        $_SESSION['cart'] = $current_cart;
    }

    header('Location: checkout.php');
    exit;
}

// すべての商品マスターデータ（JSONで管理する場合）
$products_json = json_encode([
    [
        'id' => 1,
        'name' => 'ギガビットLANケーブル',
        'price' => 1980,
        'description' => '10Gbps対応の高品質LANケーブル（2m）。',
        'image' => 'images/producting/LAN.png',
    ],
    [
        'id' => 2,
        'name' => '産業用スイッチ（ダミー）',
        'price' => 45800,
        'description' => '過酷な環境向けに最適化された産業用スイッチ。',
        'image' => 'images/producting/LAN.png',
    ],
]);

$all_products = json_decode($products_json, true);
$product_map = [];
foreach ($all_products as $p) {
    $product_map[$p['id']] = $p;
}

// カート情報の取得（セッションから）
$cart = $_SESSION['cart'] ?? [];
$cart_items = [];
$total_price = 0;

foreach ($cart as $product_id => $quantity) {
    if (isset($product_map[$product_id])) {
        $product = $product_map[$product_id];
        $subtotal = $product['price'] * $quantity;
        $cart_items[] = [
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
        $total_price += $subtotal;
    }
}

// 購入確定処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment = $_POST['payment'] ?? '';

    if ($name && $address && $payment && !empty($cart_items)) {
        // 購入情報をセッションに保存
        $_SESSION['order'] = [
            'name' => $name,
            'address' => $address,
            'payment' => $payment,
            'items' => $cart_items,
            'total' => $total_price,
            'date' => date('Y-m-d H:i:s'),
        ];
        // カートをクリア
        unset($_SESSION['cart']);
        header('Location: checkout_complete.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入ページ - VEX ECサイト</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Noto Sans JP", sans-serif; color: #1f2933; background: #f5f7fa; }
        header { background: #ffffff; border-bottom: 1px solid #e5e7eb; }
        .header-inner { max-width: 1100px; margin: 0 auto; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
        nav ul { list-style: none; display: flex; gap: 16px; }
        nav a { text-decoration: none; color: #1f2933; font-weight: 600; }
        main { max-width: 1100px; margin: 32px auto; padding: 0 16px 64px; }
        section { background: #ffffff; padding: 24px; margin-bottom: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,0.08); }
        h2 { margin-bottom: 20px; font-size: 1.5rem; border-bottom: 2px solid #2563eb; padding-bottom: 12px; }
        h3 { margin-bottom: 16px; font-size: 1.2rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; font-weight: 700; }
        tfoot tr { font-weight: 700; background: #f9fafb; font-size: 1.1rem; }
        .price-cell { text-align: right; }
        .total-row td { padding: 16px 12px; }
        form { display: flex; flex-direction: column; gap: 16px; }
        label { display: flex; flex-direction: column; gap: 8px; font-weight: 600; }
        input, select { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        input:focus, select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        button { background: #2563eb; color: #fff; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 1rem; transition: background 0.3s; }
        button:hover { background: #1d4ed8; }
        .actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 16px; }
        .continue-shopping { display: inline-block; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; border: 1px solid #2563eb; color: #2563eb; transition: background 0.3s, color 0.3s; }
        .continue-shopping:hover { background: #2563eb; color: #fff; }
        .empty-cart { text-align: center; color: #6b7280; padding: 32px; }
        .empty-cart a { display: inline-block; margin-top: 16px; background: #2563eb; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 8px; }
        footer { text-align: center; padding: 24px 16px; color: #6b7280; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1 class="logo">
                <a href="../top.php">
                    <img src="../images/logo/logo.png" width="160" height="80" alt="VEX">
                </a>
            </h1>
            <nav>
                <ul>
                    <li><a href="../top.php">トップページ</a></li>
                    <li><a href="../top.php">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h2>購入ページ（カート確認）</h2>

        <?php if (empty($cart_items)): ?>
            <section class="empty-cart">
                <p>カートに商品がありません。</p>
                <a href="../top.php">ショッピングを続ける</a>
            </section>
        <?php else: ?>
            <!-- カート商品リスト -->
            <section>
                <h3>カートの中身</h3>
                <table>
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th class="price-cell">単価</th>
                            <th class="price-cell">数量</th>
                            <th class="price-cell">小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="price-cell">¥<?php echo number_format($item['price']); ?></td>
                                <td class="price-cell"><?php echo (int)$item['quantity']; ?></td>
                                <td class="price-cell">¥<?php echo number_format($item['subtotal']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" style="text-align:right;">合計金額</td>
                            <td class="price-cell">¥<?php echo number_format($total_price); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="actions">
                    <a class="continue-shopping" href="../top.php">ショッピングを続ける</a>
                </div>
            </section>

            <!-- 配送先・支払い情報入力 -->
            <section>
                <h3>配送先・支払い情報</h3>
                <form method="POST">
                    <div class="form-row">
                        <label>
                            お名前
                            <input type="text" name="name" required>
                        </label>
                        <label>
                            ご住所
                            <input type="text" name="address" required>
                        </label>
                    </div>
                    <label>
                        支払い方法
                        <select name="payment" required>
                            <option value="">選択してください</option>
                            <option value="credit_card">クレジットカード</option>
                            <option value="bank_transfer">銀行振込</option>
                        </select>
                    </label>
                    <button type="submit">購入確定</button>
                </form>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        &copy; 2025 VEX インフラ機器販売サイト
    </footer>
</body>
</html>
