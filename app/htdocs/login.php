<?php
session_start();
require_once __DIR__ . '/db_connect.php';

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function verifyPasswordValue(string $input, ?string $stored): bool
{
    if ($stored === null || $stored === '') {
        return false;
    }
    $info = password_get_info($stored);
    if (!empty($info['algo'])) {
        return password_verify($input, $stored);
    }
    return hash_equals($stored, $input);
}

$email    = '';
$password = '';
$errors   = [];
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '')    $errors[] = 'メールアドレスを入力してください。';
    if ($password === '') $errors[] = 'パスワードを入力してください。';

    if (!$errors) {
        try {
            $pdo = new PDO(
                'mysql:host=db;dbname=vex_db;charset=utf8mb4',
                getenv('MYSQL_USER') ?: 'root',
                getenv('MYSQL_PASSWORD') ?: 'root',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            error_log('DB connect failed: ' . $e->getMessage());
            throw $e;
        }

        $stmt = $pdo->prepare(
            'SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && verifyPasswordValue($password, $user['password'])) {
            $_SESSION['user_id']    = (int)$user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: top.php');
            exit;
        }

        $login_error = 'メールアドレスまたはパスワードが違います。';
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
