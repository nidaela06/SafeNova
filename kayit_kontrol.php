<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adsoyad      = trim($_POST["adsoyad"] ?? '');
    $email        = trim($_POST["email"] ?? '');
    $sifre        = $_POST["sifre"] ?? '';
    $sifre_tekrar = $_POST["sifre_tekrar"] ?? '';

    if (empty($adsoyad) || empty($email) || empty($sifre)) {
        echo "Tüm alanlar zorunludur!";
        exit();
    }

    if ($sifre !== $sifre_tekrar) {
        echo "Şifreler aynı değil!";
        exit();
    }

    if (strlen($sifre) < 6) {
        echo "Şifre en az 6 karakter olmalıdır!";
        exit();
    }

    // E-posta zaten kayıtlı mı?
    $chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $chk->bind_param("s", $email);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        $chk->close();
        echo "Bu e-posta adresi zaten kayıtlı!";
        exit();
    }
    $chk->close();

    // Şifreyi güvenli şekilde hash'le
    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (ad_soyad, email, sifre) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $adsoyad, $email, $sifre_hash);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.php");
        exit();
    } else {
        echo "Kayıt hatası: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
