<?php
session_start();

// register_confirm からのPOST前提
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$_SESSION['registered_name']     = $_POST['name']     ?? '';
$_SESSION['registered_email']    = $_POST['email']    ?? '';
$_SESSION['registered_password'] = $_POST['password'] ?? '';
$_SESSION['registered_address']  = $_POST['address']  ?? '';

// 本当はここでDBにINSERTしてもOK（あとでやる）

// 登録完了したらログインページへ
header('Location: login.php');
exit;
