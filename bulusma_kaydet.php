<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Oturum açık değil']); exit; }
require_once 'baglanti.php';

$user_id   = (int)$_SESSION['user_id'];
$kisi_adi  = trim($_POST['kisi_adi']  ?? '');
$yakinlik  = trim($_POST['yakinlik']  ?? '');
$adres     = trim($_POST['adres']     ?? '');
$tarih     = trim($_POST['tarih']     ?? '');
$saat      = trim($_POST['saat']      ?? '');
$suphe     = trim($_POST['suphe']     ?? '');
// eski alanlar (geriye uyumluluk)
$isim      = $kisi_adi;
$nerede    = $adres;
$zaman     = $tarih && $saat ? $tarih . ' ' . $saat . ':00' : ($tarih ?? '');
$kimdir    = $yakinlik;
$not       = $suphe;

if (!$kisi_adi || !$adres || !$tarih || !$saat) {
    echo json_encode(['status'=>'error','message'=>'Kişi adı, adres, tarih ve saat zorunludur']);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO bulusmalar
        (user_id, isim, nerede, zaman, kimdir, not_alani, kisi_adi, yakinlik, adres, bulusma_tarihi, bulusma_saati, suphe_nedeni, durum)
    VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Bekliyor')
");
$stmt->bind_param('isssssssssss',
    $user_id,
    $isim, $nerede, $zaman, $kimdir, $not,
    $kisi_adi, $yakinlik, $adres, $tarih, $saat, $suphe
);

if ($stmt->execute()) {
    echo json_encode(['status'=>'ok', 'id'=>$conn->insert_id]);
} else {
    echo json_encode(['status'=>'error','message'=>$conn->error]);
}
$stmt->close();
$conn->close();
?>