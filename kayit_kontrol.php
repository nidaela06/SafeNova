<?php
session_start();
require("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adsoyad      = trim($_POST["adsoyad"] ?? '');
    $email        = trim($_POST["email"] ?? '');
    $sifre        = $_POST["sifre"] ?? '';
    $sifre_tekrar = $_POST["sifre_tekrar"] ?? '';

    if (empty($adsoyad) || empty($email) || empty($sifre)) {
        header("Location: register.php?hata=bos");
        exit();
    }
    if ($sifre !== $sifre_tekrar) {
        header("Location: register.php?hata=eslesmez");
        exit();
    }
    if (strlen($sifre) < 6) {
        header("Location: register.php?hata=kisa");
        exit();
    }

    $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $chk->execute([$email]);
    if ($chk->fetch()) {
        header("Location: register.php?hata=kayitli");
        exit();
    }

    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (ad_soyad, email, sifre, is_admin) VALUES (?, ?, ?, 0)");
    if ($stmt->execute([$adsoyad, $email, $sifre_hash])) {
        $_SESSION['user_id']  = $pdo->lastInsertId();
        $_SESSION['ad_soyad'] = $adsoyad;
        header("Location: index.php");
        exit();
    }

    header("Location: register.php?hata=sunucu");
    exit();
}

header("Location: register.php");
exit();
