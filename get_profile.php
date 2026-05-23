<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum acik degil']);
    exit;
}

include 'baglanti.php';

$user_id = (int) $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT ad_soyad, email, phone, sifre,
    dogum_tarihi, kan_grubu, kronik_hastalik, ilac_kullanimi, alerji
    FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'status'          => 'ok',
        'ad_soyad'        => $row['ad_soyad']        ?? '',
        'email'           => $row['email']            ?? '',
        'phone'           => $row['phone']            ?? '',
        'sifre'           => $row['sifre']            ?? '',
        'dogum_tarihi'    => $row['dogum_tarihi']     ?? '',
        'kan_grubu'       => $row['kan_grubu']        ?? '',
        'kronik_hastalik' => $row['kronik_hastalik']  ?? '',
        'ilac_kullanimi'  => $row['ilac_kullanimi']   ?? '',
        'alerji'          => $row['alerji']           ?? ''
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Kullanici bulunamadi']);
}

$stmt->close();
$conn->close();
?>