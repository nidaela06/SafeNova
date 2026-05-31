<?php
ini_set("session.save_path", "/tmp");
session_start();
require("baglanti.php");

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

        if (password_verify($sifre, $user['sifre'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['ad_soyad'] = $user['ad_soyad'];
            header("Location: index.php");
            exit();
        }
    }

    header("Location: login.php?hata=yanlis");
    exit();
}

header("Location: login.php");
exit();
