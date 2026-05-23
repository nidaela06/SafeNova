<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit;
}

$user_id    = $_SESSION['user_id'];
$contact_id = intval($_GET['contact_id'] ?? $_POST['contact_id'] ?? 0);

if (!$contact_id) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
    exit;
}

// Sadece kendi kaydını silebilir (user_id kontrolü)
$stmt = $conn->prepare("DELETE FROM acil_kisiler WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $contact_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'ok', 'message' => 'Kişi silindi']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Kişi bulunamadı']);
}

$stmt->close();
$conn->close();
?>