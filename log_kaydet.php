<?php
session_start();
include "baglanti.php";

if (isset($_SESSION['user_id']) && isset($_POST['type'])) {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];

    $sql = "INSERT INTO sent_logs (user_id, type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $type);
    $stmt->execute();
    echo "basarili";
}
?>