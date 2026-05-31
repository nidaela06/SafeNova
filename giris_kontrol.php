<?php
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    $sql = "SELECT * FROM users WHERE email='$email' AND sifre='$sifre'";
    $sonuc = $conn->query($sql);

    if ($sonuc->num_rows > 0) {
        $user = $sonuc->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['ad_soyad'] = $user['ad_soyad'];
        header("Location: index.php");
        exit();
    } else {
        echo "E-posta veya şifre yanlış!";
    }
}

$conn->close();
?>
