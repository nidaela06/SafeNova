<?php
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Güvenlik için verileri temizle (SQL Injection önlemi)
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $sifre = mysqli_real_escape_string($conn, $_POST["sifre"]);

    $sql = "SELECT * FROM users WHERE email='$email' AND sifre='$sifre'";
    $sonuc = $conn->query($sql);

    if ($sonuc->num_rows > 0) {
        $user = $sonuc->fetch_assoc();
        
        // Session bilgilerini doldur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['ad_soyad'] = $user['ad_soyad'];
        $_SESSION['email'] = $user['email'];

        // Başarılı girişten sonra index'e yönlendir
        header("Location: index.php");
        exit();
    } else {
        // Hata durumunda login sayfasına hata mesajıyla gönder
        header("Location: login.php?hata=hatali_giris");
        exit();
    }
}
$conn->close();
?>
