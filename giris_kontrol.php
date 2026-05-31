<?php
ini_set("session.save_path", "/tmp");
session_start();
require("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $sifre = $_POST["sifre"] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("KULLANICI BULUNAMADI - email: " . $email);
    }

    $verify = password_verify($sifre, $user['sifre']);
    die("Kullanıcı bulundu. is_admin: " . $user['is_admin'] . " | verify: " . ($verify ? 'DOĞRU' : 'YANLIŞ') . " | db_sifre_bas: " . substr($user['sifre'], 0, 10));
}
header("Location: login.php");
exit();
