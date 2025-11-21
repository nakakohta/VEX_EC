<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員登録ページ</title>
</head>
<body>

<header style="background-color: #cccccc; padding:20px; margin-bottom:40px;
               display:flex; justify-content:space-between; align-items:flex-start;">
    <div style="font-size:20px; font-weight:bold;">VEX_EC</div>
    <nav style="float:right;">
        <a href="top.php">トップへ</a>
        <a href="login.php">ログイン</a>
    </nav>
</header>

<main style="text-align:center; font-size:50px; background-color:#b9b9b9; padding:40px 0; width:80%; margin:0 auto;">
    <h1>会員登録</h1>

    <form action="register_confirm.php" method="post">

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px; column-gap:20px;">
            <label style="width:150px; text-align:left; font-size:25px;">氏名</label>
            <input type="text" name="name" style="width:300px; height:40px; font-size:20px;">
        </div>

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px; column-gap:20px;">
            <label style="width:150px; text-align:left; font-size:25px;">メール</label>
            <input type="email" name="email" style="width:300px; height:40px; font-size:20px;">
        </div>

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px; column-gap:20px;">
            <label style="width:150px; text-align:left; font-size:25px;">パスワード</label>
            <input type="password" name="password" style="width:300px; height:40px; font-size:20px;">
        </div>

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:20px; column-gap:20px;">
            <label style="width:150px; text-align:left; font-size:25px;">住所</label>
            <input type="text" name="address" style="width:300px; height:40px; font-size:20px;">
        </div>

        <button type="submit" style="font-size:20px; width:300px; height:40px;">完了</button>
    </form>
</main>

</body>
</html>
