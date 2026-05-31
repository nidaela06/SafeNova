<?php
session_start();
include("baglanti.php");

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

    $chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $chk->bind_param("s", $email);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        $chk->close();
        header("Location: register.php?hata=kayitli");
        exit();
    }
    $chk->close();

    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (ad_soyad, email, sifre, is_admin) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $adsoyad, $email, $sifre_hash);

    if ($stmt->execute()) {
        $yeni_id = $conn->insert_id;
        $_SESSION['user_id']  = $yeni_id;
        $_SESSION['ad_soyad'] = $adsoyad;
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: register.php?hata=sunucu");
        exit();
    }
}

$conn->close();
header("Location: register.php");
exit();
