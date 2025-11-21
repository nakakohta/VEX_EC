<?php
session_start();

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$email    = '';
$password = '';
$errors   = [];
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '')    $errors[] = 'メールアドレスを入力してください。';
    if ($password === '') $errors[] = 'パスワードを入力してください。';

    // 会員登録した情報（セッション）を取得
    $reg_email    = $_SESSION['registered_email']    ?? null;
    $reg_password = $_SESSION['registered_password'] ?? null;

    if (!$errors) {
        if ($reg_email === null || $reg_password === null) {
            $login_error = 'まだ会員登録が完了していません。';
        } elseif ($email === $reg_email && $password === $reg_password) {
            // ログイン成功 → top.phpへ
            header('Location: top.php');
            exit;
        } else {
            // ログイン失敗
            $login_error = 'メールアドレスまたはパスワードが違います。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
</head>
<body>

<header style="background-color:#cccccc; padding:20px; margin-bottom:70px;
               display:flex; justify-content:space-between; align-items:center;">
    <div style="font-size:20px; font-weight:bold;">VEX_EC</div>
    <nav>
        <a href="top.php" style="margin-right:10px;">トップへ</a>
        <a href="login.php">ログイン</a>
    </nav>
</header>

<main style="text-align:center; font-size:50px; background-color:#b9b9b9;
             padding:40px 0; width:80%; margin:0 auto;">
    <h1>ログイン</h1>

    <?php if ($errors): ?>
        <div style="color:red; font-size:18px; margin:20px 0;">
            <ul style="list-style:disc; text-align:left; display:inline-block;">
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($login_error): ?>
        <div style="color:red; font-size:18px; margin:10px 0;">
            <?= h($login_error) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post" style="font-size:20px; margin-top:20px;">

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px;">
            <label for="email" style="width:150px; text-align:left; font-size:25px;">メール</label>
            <input type="email" id="email" name="email"
                   style="width:300px; height:40px; font-size:20px;"
                   value="<?= h($email) ?>">
        </div>

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px;">
            <label for="password" style="width:150px; text-align:left; font-size:25px;">パスワード</label>
            <input type="password" id="password" name="password"
                   style="width:300px; height:40px; font-size:20px;">
        </div>

        <button type="submit" style="font-size:20px; width:150px; height:40px;">ログイン</button>
    </form>
</main>

</body>
</html>
