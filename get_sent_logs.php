<?php
session_start();
include "baglanti.php";

header("Content-Type: application/json");

// Kullanıcı oturum açmamışsa boş liste döndür
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["logs" => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// POST isteği ise kayıt ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? $_POST['type'] : 'acil_durum';
    
    $sql_insert = "INSERT INTO sent_logs (user_id, type, created_at) VALUES (?, ?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if ($stmt_insert) {
        $stmt_insert->bind_param("is", $user_id, $type);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
}

// GET isteği ise listeyi döndür
$sql = "SELECT id, type, created_at FROM sent_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 15";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["logs" => [], "error" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["logs" => $logs]);
exit;
?>