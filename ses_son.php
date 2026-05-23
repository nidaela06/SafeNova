<?php
session_start();
include "baglanti.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["file_path" => null]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT file_path FROM audios WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["file_path" => $row ? $row['file_path'] : null]);
exit;