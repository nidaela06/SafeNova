<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum bulunamadi.']);
    exit;
}

if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== 0) {
    echo json_encode(['status' => 'error', 'message' => 'Ses dosyasi gelmedi.']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Gelen dosyanın uzantısını belirle
$origName = $_FILES['audio']['name'] ?? 'record.webm';
$ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
if(!in_array($ext, ['webm','ogg','mp4','m4a'])) $ext = 'webm';

$filename = time() . '_' . $user_id . '.' . $ext;
$fullPath = $uploadDir . $filename;
$dbPath   = 'uploads/' . $filename;

if (!move_uploaded_file($_FILES['audio']['tmp_name'], $fullPath)) {
    echo json_encode(['status' => 'error', 'message' => 'Dosya kaydedilemedi.']);
    exit;
}

// Veritabanına kaydet
$stmt = $conn->prepare("INSERT INTO audios (user_id, file_path) VALUES (?, ?)");
if ($stmt) {
    $stmt->bind_param("is", $user_id, $dbPath);
    $stmt->execute();
    $stmt->close();
}

// Ses kaydı için de bildirim logu oluştur (İstersen)
$log_mesaj = "[SES] Yeni acil durum ses kaydi alindi.";
$s2 = $conn->prepare("INSERT INTO bildirim_log (user_id, tip, mesaj, created_at) VALUES (?, 'ses', ?, NOW())");
if ($s2) {
    $s2->bind_param("is", $user_id, $log_mesaj);
    $s2->execute();
    $s2->close();
}

echo json_encode([
    'status'    => 'ok',
    'message'   => 'Ses kaydi ve log kaydedildi.',
    'file_path' => $dbPath,
]);

$conn->close();
?>