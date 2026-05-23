<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Oturum açık değil']); exit; }
require_once 'baglanti.php';

$user_id = (int)$_SESSION['user_id'];
$id      = (int)($_GET['id'] ?? 0);

if (!$id) { echo json_encode(['status'=>'error','message'=>'Geçersiz id']); exit; }

$stmt = $conn->prepare("DELETE FROM bulusmalar WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','message'=>'Silinemedi veya yetki yok']);
}
$stmt->close();
$conn->close();
?>