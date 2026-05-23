<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

$report_id = $_POST['report_id'] ?? $_GET['report_id'] ?? 0;

if (!$report_id) {
    echo json_encode(['status' => 'error', 'message' => 'Report ID gerekli']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM locations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $report_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'ok', 'message' => 'İhbar silindi']);
$conn->close();
?>