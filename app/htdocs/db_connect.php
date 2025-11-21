<?php
// 1. データベース接続情報 (image_439e71.png の情報で更新)
//$db_host = 's313.xrea.com'; // ⚠️ 'localhost' ではない！
//$db_name = 'vexweb_db';     //
//$db_user = 'vexweb_db';     //
//$db_pass = 'Test1';         //
//$db_charset = 'utf8mb4';

// 1. データベース接続情報 (image_439e71.png の情報で更新)
//本番環境に合わせるときは下記の内容（10行目から15行目）を本番用に書き換える
$db_host = 'db'; // ⚠️ 'localhost' ではない！
$db_name = 'vex_db';     //
$db_user = 'vex_user';     //
$db_pass = 'vex_password';         //
$db_charset = 'utf8mb4';

// 2. データベース接続処理 (PDO)
$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    echo "データベースの接続に失敗しました: " . $e->getMessage();
    exit;
}
?>