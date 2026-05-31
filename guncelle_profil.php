<?php
session_start();
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $sifre = $_POST["sifre"] ?? '';

    if (empty($email) || empty($sifre)) {
        echo "E-posta veya şifre boş bırakılamaz!";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Hem hash'li hem düz metin şifreye destek (geçiş dönemi)
        $giris_ok = false;
        if (password_verify($sifre, $user['sifre'])) {
            $giris_ok = true;
        } elseif ($user['sifre'] === $sifre) {
            // Eski düz metin şifre → hash'e yükselt
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
    echo "E-posta veya şifre yanlış!";
}
?>
