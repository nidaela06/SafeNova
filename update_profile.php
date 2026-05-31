<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum açık değil']);
    exit;
}

include 'baglanti.php';

$user_id  = (int) $_SESSION['user_id'];
$ad_soyad = trim($_POST['ad_soyad'] ?? '');
$email    = trim($_POST['email']    ?? '');
$phone    = trim($_POST['phone']    ?? '');
$sifre    = $_POST['sifre'] ?? '';

if (empty($ad_soyad)) {
    echo json_encode(['status' => 'error', 'message' => 'Ad Soyad boş olamaz']);
    exit;
}

// Email başka kullanıcıda var mı?
if (!empty($email)) {
    $chk = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $chk->bind_param("si", $email, $user_id);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Bu e-posta başka bir hesapta kullanılıyor']);
        exit;
    }
    $chk->close();
}

// Şifre değiştirilecek mi?
if (!empty($sifre)) {
    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET ad_soyad=?, email=?, phone=?, sifre=? WHERE id=?");
    $stmt->bind_param("ssssi", $ad_soyad, $email, $phone, $sifre_hash, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET ad_soyad=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $ad_soyad, $email, $phone, $user_id);
}

if ($stmt->execute()) {
    $_SESSION['ad_soyad'] = $ad_soyad;
    echo json_encode(['status' => 'ok', 'message' => 'Bilgiler güncellendi']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Güncelleme başarısız: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
