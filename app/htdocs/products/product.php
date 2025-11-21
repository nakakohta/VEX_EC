<?php
$products = [
	1 => [
		'id' => 1,
		'name' => 'ギガビットLANケーブル',
		'price' => 1980,
		'description' => '10Gbps対応の高品質LANケーブル（2m）。',
		'image' => '../images/producting/LAN.png',
	],
	2 => [
		'id' => 2,
		'name' => '産業用スイッチ（ダミー）',
		'price' => 45800,
		'description' => '過酷な環境向けに最適化された産業用スイッチ。',
		'image' => '../images/producting/LAN.png',
	],
];

$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = $product_id && isset($products[$product_id]) ? $products[$product_id] : null;

if (!$product) {
	http_response_code(404);
	echo '<!DOCTYPE html><html lang="ja"><meta charset="UTF-8"><body>商品が見つかりません。</body></html>';
	exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?> | VEX ECサイト</title>
	<style>
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body { font-family: "Noto Sans JP", sans-serif; color: #1f2933; background: #f5f7fa; }
		header { background: #ffffff; border-bottom: 1px solid #e5e7eb; }
		.header-inner { max-width: 1100px; margin: 0 auto; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
		nav ul { list-style: none; display: flex; gap: 16px; }
		nav a { text-decoration: none; color: #2563eb; font-weight: 600; }
		main { max-width: 1100px; margin: 32px auto; padding: 0 16px 64px; }
		.product-detail { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; background: #ffffff; padding: 32px; border-radius: 16px; box-shadow: 0 16px 40px rgba(15,23,42,0.12); }
		.product-detail img { width: 100%; max-height: 360px; object-fit: contain; border-radius: 16px; background: #f3f4f6; padding: 24px; }
		.info h2 { font-size: 1.8rem; margin-bottom: 12px; }
		.info p { margin-bottom: 16px; color: #4b5563; line-height: 1.7; }
		.price { font-size: 1.5rem; font-weight: 700; color: #2563eb; margin-bottom: 20px; }
		form { display: flex; flex-direction: column; gap: 16px; }
		label { font-weight: 600; display: flex; flex-direction: column; gap: 8px; }
		input[type="number"] { padding: 10px 12px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 1rem; }
		button { background: #2563eb; color: #fff; border: none; padding: 12px; border-radius: 999px; cursor: pointer; font-weight: 700; font-size: 1rem; transition: background 0.2s ease; }
		button:hover { background: #1d4ed8; }
		.breadcrumbs { margin-bottom: 24px; color: #6b7280; font-size: 0.9rem; }
		.breadcrumbs a { color: #2563eb; text-decoration: none; }
		footer { text-align: center; padding: 32px 16px; color: #6b7280; font-size: 0.9rem; }
	</style>
</head>
<body>
	<header>
		<div class="header-inner">
			<h1 class="logo">
				<a href="../top.php">
					<img src="../images/logo/logo.png" width="140" height="70" alt="VEX">
				</a>
			</h1>
			<nav>
				<ul>
					<li><a href="../top.php">トップ</a></li>
					<li><a href="checkout.php">カートを見る</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<main>
		<div class="breadcrumbs"><a href="../top.php">商品一覧</a> &gt; <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></div>
		<section class="product-detail">
			<img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
			<div class="info">
				<h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
				<p class="price">¥<?php echo number_format($product['price']); ?></p>
				<p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
				<form id="add-to-cart-form" method="POST" action="checkout.php">
					<label>
						数量
						<input type="number" id="quantity" name="quantity" min="1" value="1">
					</label>
					<input type="hidden" name="cart_json" id="cart_json">
					<button type="submit">カートに入れる</button>
				</form>
			</div>
		</section>
	</main>

	<footer>
		&copy; <?php echo date('Y'); ?> VEX インフラ機器販売サイト
	</footer>

	<script>
		const form = document.getElementById('add-to-cart-form');
		const quantityInput = document.getElementById('quantity');
		const cartJsonInput = document.getElementById('cart_json');
		const productPayload = <?php echo json_encode(
			[
				'id' => (int) $product['id'],
				'name' => $product['name'],
				'price' => (int) $product['price'],
			],
			JSON_UNESCAPED_UNICODE
		); ?>;

		form.addEventListener('submit', function () {
			const quantity = Math.max(1, parseInt(quantityInput.value, 10) || 1);
			const cartData = [{
				...productPayload,
				quantity,
				subtotal: productPayload.price * quantity
			}];
			cartJsonInput.value = JSON.stringify(cartData);
		});
	</script>
</body>
</html>
