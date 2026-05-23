<?php
session_start();
include "baglanti.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["ihbarlar" => []]);
    exit;
}

$sql = "SELECT id, user_id, lat, lng, created_at FROM locations ORDER BY created_at DESC LIMIT 50";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$ihbarlar = [];
while ($row = $result->fetch_assoc()) {
   
    $ihbarlar[] = $row;
}

echo json_encode(["ihbarlar" => $ihbarlar]);
exit;