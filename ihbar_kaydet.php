<?php
session_start();
header('Content-Type: application/json');

include "baglanti.php";

$kullanici_id = $_SESSION['user_id'] ?? 0;
$lat = $_POST['lat'] ?? '';
$lng = $_POST['lng'] ?? '';

if (!$lat || !$lng) {
    echo json_encode(["status" => "error", "message" => "Konum alınamadı"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO locations (user_id, lat, lng) VALUES (?, ?, ?)");
$stmt->bind_param("idd", $kullanici_id, $lat, $lng);
$stmt->execute();

echo json_encode(["status" => "ok"]);
$conn->close();
?>