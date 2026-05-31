<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum açık değil']);
    exit;
}

require 'baglanti.php';

$user_id        = (int) $_SESSION['user_id'];
$ad_soyad       = trim($_POST['ad_soyad'] ?? '');
$email          = trim($_POST['email'] ?? '');
$phone          = trim($_POST['phone'] ?? '');
$sifre          = $_POST['sifre'] ?? '';
$dogum_tarihi   = trim($_POST['dogum_tarihi'] ?? '');
$kan_grubu      = trim($_POST['kan_grubu'] ?? '');
$kronik         = trim($_POST['kronik_hastalik'] ?? '');
$ilac           = trim($_POST['ilac_kullanimi'] ?? '');
$alerji         = trim($_POST['alerji'] ?? '');

if (empty($ad_soyad)) {
    echo json_encode(['status' => 'error', 'message' => 'Ad Soyad boş olamaz']);
    exit;
}

// Email boşsa NULL yap, doluysa duplicate kontrolü yap
$email_val = !empty($email) ? $email : null;

if ($email_val !== null) {
    $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $chk->execute([$email_val, $user_id]);
    if ($chk->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Bu e-posta başka bir hesapta kullanılıyor']);
        exit;
    }
}

$dogum_val = !empty($dogum_tarihi) ? $dogum_tarihi : null;

if (!empty($sifre)) {
    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET ad_soyad=?, email=?, phone=?, sifre=?,
        dogum_tarihi=?, kan_grubu=?, kronik_hastalik=?, ilac_kullanimi=?, alerji=?
        WHERE id=?");
    $stmt->execute([$ad_soyad, $email_val, $phone, $sifre_hash,
        $dogum_val, $kan_grubu, $kronik, $ilac, $alerji, $user_id]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET ad_soyad=?, email=?, phone=?,
        dogum_tarihi=?, kan_grubu=?, kronik_hastalik=?, ilac_kullanimi=?, alerji=?
        WHERE id=?");
    $stmt->execute([$ad_soyad, $email_val, $phone,
        $dogum_val, $kan_grubu, $kronik, $ilac, $alerji, $user_id]);
}

$_SESSION['ad_soyad'] = $ad_soyad;
echo json_encode(['status' => 'ok', 'message' => 'Bilgiler güncellendi']);
?>
