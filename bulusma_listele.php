<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) { echo json_encode(['status'=>'error','message'=>'Oturum açık değil']); exit; }
require_once 'baglanti.php';

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT
        id,
        COALESCE(kisi_adi, isim, '')                           AS kisi_adi,
        COALESCE(yakinlik, kimdir, '')                          AS yakinlik,
        COALESCE(adres, nerede, '')                             AS adres,
        COALESCE(bulusma_tarihi, DATE_FORMAT(zaman,'%Y-%m-%d'), '') AS bulusma_tarihi,
        COALESCE(bulusma_saati, DATE_FORMAT(zaman,'%H:%i'), '') AS bulusma_saati,
        COALESCE(suphe_nedeni, not_alani, '')                   AS suphe_nedeni,
        durum,
        DATE_FORMAT(created_at, '%d.%m.%Y %H:%i')              AS kayit_tarihi
    FROM bulusmalar
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result     = $stmt->get_result();
$bulusmalar = [];
while ($row = $result->fetch_assoc()) {
    $bulusmalar[] = $row;
}
echo json_encode(['status'=>'ok', 'bulusmalar'=>$bulusmalar]);
$stmt->close();
$conn->close();
?>