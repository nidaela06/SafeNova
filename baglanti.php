<?php
ini_set("session.save_path", "/tmp");

$host       = "tramway.proxy.rlwy.net";
$kullanici  = "root";
$sifre      = "rtFqFcMRVszREQvMUPPqDcdonJDGwsRU";
$veritabani = "railway";
$port       = 51401;

$conn = new mysqli($host, $kullanici, $sifre, $veritabani, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // JSON isteği mi (AJAX) yoksa HTML sayfası mı?
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    $isJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
    
    if ($isAjax || $isJson) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["status" => "error", "message" => "Veritabanı bağlantı hatası."]);
    } else {
        // HTML sayfalarında güzel hata göster
        http_response_code(503);
        echo '<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>SafeNova – Bağlantı Hatası</title>
        <style>*{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;background:linear-gradient(135deg,#ff4fa0,#8a2be2);min-height:100vh;display:flex;align-items:center;justify-content:center;}
        .box{background:white;border-radius:20px;padding:40px;text-align:center;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,0.2);}
        h2{color:#e53935;margin-bottom:12px;font-size:20px;}
        p{color:#666;font-size:14px;line-height:1.6;}
        a{display:inline-block;margin-top:20px;padding:12px 28px;background:#ff2e7a;color:white;border-radius:12px;text-decoration:none;font-size:14px;}
        </style></head><body>
        <div class="box">
          <h2>⚠️ Bağlantı Hatası</h2>
          <p>Veritabanına bağlanılamıyor. Lütfen birkaç saniye bekleyip tekrar deneyin.</p>
          <a href="javascript:location.reload()">🔄 Tekrar Dene</a>
        </div></body></html>';
    }
    exit();
}
?>
