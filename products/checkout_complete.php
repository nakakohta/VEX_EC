<?php
session_start();

// 購入情報がない場合はトップページへリダイレクト
if (!isset($_SESSION['order'])) {
    header('Location: ../index.php');
    exit;
}

$order = $_SESSION['order'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入完了 - VEX ECサイト</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Noto Sans JP", sans-serif; color: #1f2933; background: #f5f7fa; }
        header { background: #ffffff; border-bottom: 1px solid #e5e7eb; }
        .header-inner { max-width: 1100px; margin: 0 auto; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
        nav ul { list-style: none; display: flex; gap: 16px; }
        nav a { text-decoration: none; color: #1f2933; font-weight: 600; }
        main { max-width: 1100px; margin: 32px auto; padding: 0 16px 64px; }
        .completion-container { background: #ffffff; padding: 48px 32px; border-radius: 16px; box-shadow: 0 4px 12px rgba(15,23,42,0.08); text-align: center; }
        .icon { font-size: 4rem; margin-bottom: 24px; }
        h2 { font-size: 2rem; color: #059669; margin-bottom: 12px; }
        .message { font-size: 1.1rem; color: #4b5563; margin-bottom: 32px; line-height: 1.8; }
        .order-info { background: #f9fafb; padding: 24px; border-radius: 12px; margin: 32px 0; text-align: left; }
        .order-info h3 { font-size: 1.2rem; margin-bottom: 16px; border-bottom: 2px solid #2563eb; padding-bottom: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #6b7280; }
        .info-value { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; font-weight: 700; }
        .price-cell { text-align: right; }
        .total-row { font-weight: 700; background: #f9fafb; }
        .action-buttons { display: flex; justify-content: center; gap: 16px; margin-top: 32px; }
        .btn-primary { background: #2563eb; color: #fff; border: none; padding: 12px 32px; border-radius: 999px; cursor: pointer; font-weight: 700; text-decoration: none; display: inline-block; transition: background 0.3s; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: transparent; color: #2563eb; border: 2px solid #2563eb; padding: 10px 30px; border-radius: 999px; cursor: pointer; font-weight: 700; text-decoration: none; display: inline-block; transition: background 0.3s, color 0.3s; }
        .btn-secondary:hover { background: #2563eb; color: #fff; }
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
        <section class="completion-container">
            <div class="icon">✓</div>
            <h2>ご購入ありがとうございました！</h2>
            <p class="message">
                ご注文の確認が完了いたしました。<br>
                ご指定の住所に商品をお届けいたします。
            </p>

            <!-- 注文詳細情報 -->
            <div class="order-info">
                <h3>ご注文詳細</h3>
                
                <div class="info-row">
                    <span class="info-label">注文日時</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['date'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">お客様名</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">配送先住所</span>
                    <span class="info-value"><?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">支払い方法</span>
                    <span class="info-value">
                        <?php
                            $payment_labels = [
                                'credit_card' => 'クレジットカード',
                                'bank_transfer' => '銀行振込',
                            ];
                            echo htmlspecialchars($payment_labels[$order['payment']] ?? $order['payment'], ENT_QUOTES, 'UTF-8');
                        ?>
                    </span>
                </div>
            </div>

            <!-- 商品一覧 -->
            <div class="order-info">
                <h3>ご購入商品</h3>
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
                        <?php foreach ($order['items'] as $item): ?>
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
                            <td colspan="3" style="text-align:right;">ご合計金額</td>
                            <td class="price-cell">¥<?php echo number_format($order['total']); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- アクションボタン -->
            <div class="action-buttons">
                <a href="../top.php" class="btn-primary">トップページへ戻る</a>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2025 VEX インフラ機器販売サイト
    </footer>
</body>
</html>