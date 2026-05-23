<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

$user_id  = $_SESSION['user_id'];
$ad_soyad = trim($_POST['name']  ?? '');
$telefon  = trim($_POST['phone'] ?? '');
$yakinlik = trim($_POST['rel']   ?? '');

if (!$ad_soyad || !$telefon) {
    echo json_encode(['status' => 'error', 'message' => 'Ad ve telefon zorunludur']);
    exit;
}

// Aynı kullanıcı için aynı numara daha önce eklenmiş mi?
$chk = $conn->prepare("SELECT id FROM acil_kisiler WHERE user_id = ? AND telefon = ?");
$chk->bind_param("is", $user_id, $telefon);
$chk->execute();
$chk->store_result();
if ($chk->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Bu telefon numarası zaten kayıtlı']);
    $chk->close();
    exit;
}
$chk->close();

$stmt = $conn->prepare("INSERT INTO acil_kisiler (user_id, ad_soyad, telefon, yakinlik) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $ad_soyad, $telefon, $yakinlik);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'message' => 'Kişi eklendi', 'id' => $conn->insert_id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>