<?php
$products = [
    [
        'id' => 1,
        'name' => 'ギガビットLANケーブル',
        'price' => 1980,
        'description' => '10Gbps対応の高品質LANケーブル（2m）。，',
        'image' => 'images/producting/LAN.png',
    ],
    [
        'id' => 2,
        'name' => '産業用スイッチ（ダミー）',
        'price' => 45800,
        'description' => '過酷な環境向けに最適化された産業用スイッチ。，',
        'image' => 'images/producting/LAN.png',
    ],
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEX ECサイト</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Noto Sans JP", sans-serif; color: #1f2933; background: #f5f7fa; }
        header { background: #ffffff; border-bottom: 1px solid #e5e7eb; }
        .header-inner { max-width: 1100px; margin: 0 auto; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
        nav ul { list-style: none; display: flex; gap: 16px; }
        nav a { text-decoration: none; color: #1f2933; font-weight: 600; }
        main { max-width: 1100px; margin: 32px auto; padding: 0 16px 64px; }
        .hero { margin-bottom: 32px; background: linear-gradient(135deg, #eff6ff, #dbeafe); padding: 32px; border-radius: 16px; text-align: center; }
        .hero h1 { font-size: 2rem; margin-bottom: 12px; }
        .hero p { color: #4b5563; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; }
        .product-card { background: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 12px 30px rgba(15,23,42,0.08); display: flex; flex-direction: column; gap: 12px; }
        .product-card img { width: 100%; height: 180px; object-fit: contain; border-radius: 12px; background: #f3f4f6; }
        .product-card h3 { font-size: 1.1rem; }
        .product-card p { color: #6b7280; font-size: 0.9rem; }
        .product-card .price { font-size: 1.2rem; font-weight: 700; color: #2563eb; }
        .product-card button { margin-top: auto; background: #2563eb; color: #fff; border: none; padding: 10px; border-radius: 999px; cursor: pointer; font-weight: 600; }
        footer { text-align: center; padding: 24px 16px; color: #6b7280; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <h1 class="logo">
                <a href="top.php">
                    <img src="images/logo/logo.png" width="160" height="80" alt="VEX">
                </a>
            </h1>
            <nav>
                <ul>
                    <li><a href="">ログイン</a></li>
                    <li><a href="">会員登録</a></li>
                    <li><a href="products/checkout.php">カートを見る</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>次世代インフラ機器を、すぐに。</h1>
            <p>VEXおすすめのネットワーク機器をダミーデータでご紹介しています。</p>
        </section>

        <section>
            <h2 style="margin-bottom:16px;">商品一覧</h2>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="price">¥<?php echo number_format($product['price']); ?></div>
                        <a class="details-link" href="products/product.php?id=<?php echo urlencode($product['id']); ?>">
                            詳細を見る
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> VEX インフラ機器販売サイト
    </footer>
</body>
</html>










