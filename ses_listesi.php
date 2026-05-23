<?php
session_start();
include "baglanti.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["kayitlar" => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, file_path, created_at FROM audios WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$kayitlar = [];
while ($row = $result->fetch_assoc()) {
    $kayitlar[] = $row;
}

echo json_encode(["kayitlar" => $kayitlar]);
exit;