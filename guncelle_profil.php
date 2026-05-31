<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum açık değil']);
    exit;
}

include 'baglanti.php';

$user_id         = (int) $_SESSION['user_id'];
$ad_soyad        = trim($_POST['ad_soyad']        ?? '');
$email           = trim($_POST['email']            ?? '');
$phone           = trim($_POST['phone']            ?? '');
$sifre           = $_POST['sifre']                 ?? '';
$dogum_tarihi    = trim($_POST['dogum_tarihi']     ?? '');
$kan_grubu       = trim($_POST['kan_grubu']        ?? '');
$kronik_hastalik = trim($_POST['kronik_hastalik']  ?? '');
$ilac_kullanimi  = trim($_POST['ilac_kullanimi']   ?? '');
$alerji          = trim($_POST['alerji']           ?? '');

if (empty($ad_soyad)) {
    echo json_encode(['status' => 'error', 'message' => 'Ad Soyad boş olamaz']);
    exit;
}

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

$dogum_tarihi_val = !empty($dogum_tarihi) ? $dogum_tarihi : null;

if (!empty($sifre)) {
    // Şifreyi hash'le
    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET ad_soyad=?, email=?, phone=?, sifre=?,
        dogum_tarihi=?, kan_grubu=?, kronik_hastalik=?, ilac_kullanimi=?, alerji=?
        WHERE id=?");
    $stmt->bind_param("sssssssssi",
        $ad_soyad, $email, $phone, $sifre_hash,
        $dogum_tarihi_val, $kan_grubu, $kronik_hastalik, $ilac_kullanimi, $alerji,
        $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET ad_soyad=?, email=?, phone=?,
        dogum_tarihi=?, kan_grubu=?, kronik_hastalik=?, ilac_kullanimi=?, alerji=?
        WHERE id=?");
    $stmt->bind_param("ssssssssi",
        $ad_soyad, $email, $phone,
        $dogum_tarihi_val, $kan_grubu, $kronik_hastalik, $ilac_kullanimi, $alerji,
        $user_id);
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
