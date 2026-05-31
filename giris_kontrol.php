<?php
session_start();
require("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $sifre = $_POST["sifre"] ?? '';

    if (empty($email) || empty($sifre)) {
        header("Location: login.php?hata=bos");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 0");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($sifre, $user['sifre'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['ad_soyad'] = $user['ad_soyad'];
        header("Location: index.php");
        exit();
    }

    header("Location: login.php?hata=yanlis");
    exit();
}

header("Location: login.php");
exit();
