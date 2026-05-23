<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adsoyad = $_POST["adsoyad"];
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];
    $sifre_tekrar = $_POST["sifre_tekrar"];

    if ($sifre != $sifre_tekrar) {
        echo "Şifreler aynı değil!";
        exit();
    }

    $sql = "INSERT INTO users (ad_soyad, email, sifre) VALUES ('$adsoyad', '$email', '$sifre')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        echo "Kayıt hatası: " . $conn->error;
    }
}

$conn->close();
?>