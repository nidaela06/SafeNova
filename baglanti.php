<?php
$host       = "tramway.proxy.rlwy.net";
$kullanici  = "root";
$db_sifre   = "rtFqFcMRVszREQvMUPPqDcdonJDGwsRU";
$veritabani = "railway";
$port       = 51401;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$veritabani;charset=utf8mb4",
        $kullanici,
        $db_sifre,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>
