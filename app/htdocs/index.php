<?php
// ステップ3で作成したDB接続ファイルを読み込む
require_once './db_connect.php';

echo "<h1>ECサイト 接続テスト</h1>";

try {
    // 'products' テーブルからデータを取得するSQL
    $sql = "SELECT * FROM products LIMIT 5"; // ひとまず5件だけ取得
    
    // SQLを実行
    $stmt = $pdo->query($sql);

    echo "<p>データベース（{$db_name}）への接続に成功しました。</p>";
    echo "<h2>Productsテーブル (最大5件)</h2>";
    
    // 取得したデータを表示
    echo "<ul>";
    while ($row = $stmt->fetch()) {
        // 'products' テーブルに 'name' または 'product_name' カラムがあると想定
        // 適切なカラム名に変更してください (例: $row['id'])
        $product_name = isset($row['name']) ? $row['name'] : (isset($row['product_name']) ? $row['product_name'] : $row['id']);
        
        echo "<li>" . htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8') . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    // SQLエラーや接続エラー
    echo "<p><strong>エラー:</strong> データベース処理に失敗しました。</p>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>（ヒント：ステップ5のリモート接続許可を再確認してください）</p>";
}
?>