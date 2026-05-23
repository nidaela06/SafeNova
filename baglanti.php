<?php
$host       = "localhost";
$kullanici  = "root";
$sifre      = "";
$veritabani = "safenova_db";

$conn = new mysqli($host, $kullanici, $sifre, $veritabani);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Bağlantı hatası: " . $conn->connect_error]));
}
?>