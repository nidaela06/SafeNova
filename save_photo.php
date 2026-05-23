<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
    echo json_encode(['status' => 'error', 'message' => 'Fotoğraf gelmedi veya yükleme hatası']);
    exit;
}

$user_id = $_SESSION['user_id'];

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = time() . '_' . $user_id . '.png';
$fullPath = $uploadDir . $filename;
$dbPath   = 'uploads/' . $filename;

if (!move_uploaded_file($_FILES['photo']['tmp_name'], $fullPath)) {
    echo json_encode(['status' => 'error', 'message' => 'Dosya sunucuya taşınamadı']);
    exit;
}

// Veritabanına kaydet
$stmt = $conn->prepare("INSERT INTO photos (user_id, file_path) VALUES (?, ?)");
if ($stmt) {
    $stmt->bind_param("is", $user_id, $dbPath);
    $stmt->execute();
    $stmt->close();
}

// Bildirim logu (tablo yoksa sessizce geç)
$s2 = $conn->prepare("INSERT INTO bildirim_log (user_id, tip, mesaj, created_at) VALUES (?, 'foto', ?, NOW())");
if ($s2) {
    $log_mesaj = "[FOTOGRAF] Yeni acil durum fotoğrafı yüklendi: $dbPath";
    $s2->bind_param("is", $user_id, $log_mesaj);
    $s2->execute();
    $s2->close();
}

$conn->close();

// JS tarafı file_path key'ini bekliyor — dosya hem 'file_path' hem 'dosya' ile dönüyor
echo json_encode([
    'status'    => 'ok',
    'message'   => 'Fotoğraf kaydedildi',
    'file_path' => $dbPath,   // index.php JS bunu kullanıyor
    'dosya'     => $dbPath,   // geriye dönük uyumluluk
]);
?>