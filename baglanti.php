<?php
// Railway Canlı Veritabanı Bilgileri
$host       = "tramway.proxy.rlwy.net";
$kullanici  = "root";
$sifre      = "rtFqFcMRVszREQvMUPPqDcdonJDGwsRU";
$veritabani = "railway"; // HeidiSQL'de sol tarafta gördüğün isim
$port       = 51401;     // Railway'in sana verdiği özel port

// Bağlantıyı kurarken port numarasını da ekliyoruz
$conn = new mysqli($host, $kullanici, $sifre, $veritabani, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Bağlantı hatası: " . $conn->connect_error]));
}
?>
