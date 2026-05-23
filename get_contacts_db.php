<?php
session_start();
include "baglanti.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['contacts' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT id, ad_soyad, telefon, yakinlik FROM acil_kisiler WHERE user_id = ? ORDER BY created_at ASC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(['contacts' => $contacts]);
?>