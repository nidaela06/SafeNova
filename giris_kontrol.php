<?php
session_start(); // 1. Mutlaka en üstte olmalı
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $sifre = mysqli_real_escape_string($conn, $_POST["sifre"]);

    // Sorguyu çalıştırıyoruz
    $sql = "SELECT * FROM users WHERE email='$email' AND sifre='$sifre'";
    $sonuc = $conn->query($sql);

    if ($sonuc && $sonuc->num_rows > 0) {
        $user = $sonuc->fetch_assoc();
        
        // --- KRİTİK NOKTA ---
        // Veritabanındaki sütun isimlerini kontrol et! 
        // Eğer veritabanında 'id' yerine 'ID' veya 'kullanici_id' yazıyorsa burayı ona göre düzelt.
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['ad_soyad'] = $user['ad_soyad'];
        
        // Oturumu hemen diske kaydet (Yönlendirmeden önce verinin kaybolmasını engeller)
        session_write_close(); 
        
        header("Location: index.php");
        exit();
    } else {
        // Giriş başarısızsa
        header("Location: login.php?hata=yanlis_bilgi");
        exit();
    }
}
?>
