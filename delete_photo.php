<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

$photo_id = $_POST['photo_id'] ?? $_GET['photo_id'] ?? 0;

if (!$photo_id) {
    echo json_encode(['status' => 'error', 'message' => 'Photo ID gerekli']);
    exit;
}

$stmt = $conn->prepare("SELECT file_path FROM photos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $photo_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$photo = $result->fetch_assoc();
$stmt->close();

$fullPath = __DIR__ . '/' . $photo['file_path'];
if ($photo && file_exists($fullPath)) {
    unlink($fullPath);
}

$stmt = $conn->prepare("DELETE FROM photos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $photo_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'ok', 'message' => 'Fotoğraf silindi']);
$conn->close();
?>