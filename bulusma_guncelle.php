<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Oturum açık değil']); exit; }
require_once 'baglanti.php';

$user_id  = (int)$_SESSION['user_id'];
$id       = (int)($_POST['id'] ?? 0);
$kisi_adi = trim($_POST['kisi_adi']  ?? '');
$yakinlik = trim($_POST['yakinlik']  ?? '');
$adres    = trim($_POST['adres']     ?? '');
$tarih    = trim($_POST['tarih']     ?? '');
$saat     = trim($_POST['saat']      ?? '');
$suphe    = trim($_POST['suphe']     ?? '');

if (!$id || !$kisi_adi || !$adres || !$tarih || !$saat) {
    echo json_encode(['status'=>'error','message'=>'Zorunlu alanlar eksik']);
    exit;
}

$zaman = $tarih . ' ' . $saat . ':00';

$stmt = $conn->prepare("
    UPDATE bulusmalar SET
        kisi_adi        = ?,
        yakinlik        = ?,
        adres           = ?,
        bulusma_tarihi  = ?,
        bulusma_saati   = ?,
        suphe_nedeni    = ?,
        isim            = ?,
        nerede          = ?,
        zaman           = ?,
        kimdir          = ?,
        not_alani       = ?
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param('sssssssssssii',
    $kisi_adi, $yakinlik, $adres, $tarih, $saat, $suphe,
    $kisi_adi, $adres, $zaman, $yakinlik, $suphe,
    $id, $user_id
);

if ($stmt->execute() && $stmt->affected_rows >= 0) {
    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','message'=>$conn->error]);
}
$stmt->close();
$conn->close();
?>