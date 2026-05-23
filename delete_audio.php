<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

$audio_id = $_POST['audio_id'] ?? $_GET['audio_id'] ?? 0;

if (!$audio_id) {
    echo json_encode(['status' => 'error', 'message' => 'Audio ID gerekli']);
    exit;
}

$stmt = $conn->prepare("SELECT file_path FROM audios WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $audio_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$audio = $result->fetch_assoc();
$stmt->close();

$fullPath = __DIR__ . '/' . $audio['file_path'];
if ($audio && file_exists($fullPath)) {
    unlink($fullPath);
}

$stmt = $conn->prepare("DELETE FROM audios WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $audio_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'ok', 'message' => 'Ses kaydı silindi']);
$conn->close();
?>