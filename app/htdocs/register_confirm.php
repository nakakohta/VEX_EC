<?php
session_start();

// 入力値受け取り
$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');
$address  = trim($_POST['address']  ?? '');

$errors = [];

if ($name === '')     $errors[] = "氏名が未入力です。";
if ($email === '')    $errors[] = "メールが未入力です。";
if ($password === '') $errors[] = "パスワードが未入力です。";
if ($address === '')  $errors[] = "住所が未入力です。";

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録情報確認</title>
</head>
<body>

<header style="background-color:#cccccc; padding:20px; margin-bottom:40px;
               display:flex; justify-content:space-between; align-items:flex-start;">
    <div style="font-size:20px; font-weight:bold;">VEX_EC</div>
    <nav style="float:right;">
        <a href="top.php" style="margin-right:10px;">トップへ</a>
        <a href="login.php">ログイン</a>
    </nav>
</header>

<main style="text-align:center; font-size:50px; background-color:#b9b9b9; padding:40px 0; width:80%; margin:0 auto;">
<?php if ($errors): ?>

    <h1 style="font-size:36px; margin-bottom:20px;">エラーがあります</h1>
    <ul style="color:red; font-size:20px; display:inline-block; text-align:left;">
        <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
        <?php endforeach; ?>
    </ul>
    <div style="margin-top:20px;">
        <a href="register.php"><button style="font-size:20px; width:150px; height:40px;">戻る</button></a>
    </div>

<?php else: ?>

    <h1 style="font-size:36px; margin-bottom:20px;">登録情報確認</h1>

    <div style="font-size:22px; margin-bottom:10px;">氏名：<?= h($name) ?></div>
    <div style="font-size:22px; margin-bottom:10px;">メール：<?= h($email) ?></div>
    <div style="font-size:22px; margin-bottom:10px;">パスワード：セキュリティのため非表示</div>
    <div style="font-size:22px; margin-bottom:20px;">住所：<?= h($address) ?></div>

    <!-- 登録確定：セッションに保存して login.php へ -->
    <form action="register_complete.php" method="post" style="margin-bottom:10px;">
        <input type="hidden" name="name"     value="<?= h($name) ?>">
        <input type="hidden" name="email"    value="<?= h($email) ?>">
        <input type="hidden" name="password" value="<?= h($password) ?>">
        <input type="hidden" name="address"  value="<?= h($address) ?>">
        <button type="submit" style="font-size:20px; width:200px; height:40px;">登録確定</button>
    </form>

    <div>
        <a href="register.php"><button style="font-size:18px; width:150px; height:36px;">修正する</button></a>
    </div>

<?php endif; ?>
</main>

</body>
</html>
