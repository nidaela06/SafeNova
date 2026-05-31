<?php
ob_start();
ini_set("session.save_path", "/tmp");
session_start();

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    exit();
}

require("baglanti.php");

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $sifre = $_POST["sifre"] ?? '';

    if (empty($email) || empty($sifre)) {
        header("Location: login.php?hata=bos");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $giris_ok = false;

        if (password_verify($sifre, $user['sifre'])) {
            $giris_ok = true;
        } elseif ($user['sifre'] === $sifre) {
            $yeni_hash = password_hash($sifre, PASSWORD_BCRYPT);
            $upd = $conn->prepare("UPDATE users SET sifre = ? WHERE id = ?");
            $upd->bind_param("si", $yeni_hash, $user['id']);
            $upd->execute();
            $upd->close();
            $giris_ok = true;
        }

        if ($giris_ok) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['ad_soyad'] = $user['ad_soyad'];
            $stmt->close();
            $conn->close();
            header("Location: index.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
    header("Location: login.php?hata=yanlis");
    exit();
}

$conn->close();
header("Location: login.php");
exit();
