<?php
/**
 * SafeNova - Genel Konfigürasyon
 */

define('APP_NAME', 'SafeNova Admin Panel');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'production');

// URL Ayarları - Railway ortamı için dinamik
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host_url = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
define('BASE_URL', $host_url . '/');
define('UPLOAD_DIR', __DIR__ . '/uploads/');

// Zaman Dilimi
date_default_timezone_set('Europe/Istanbul');

// İstatistik Parametreleri
define('FAKE_REPORT_THRESHOLD', 5);
define('ADMIN_SESSION_TIMEOUT', 3600);

// Sayfalama
define('ITEMS_PER_PAGE', 20);

// İhbar Durumları
$REPORT_STATUS = [
    'bekliyor'   => 'Bekleniyor',
    'inceleniyor'=> 'İnceleniyor',
    'mudahale'   => 'Müdahale Edildi',
    'kapali'     => 'Kapalı'
];

// Polis Durumları
$POLICE_STATUS = [
    'musait'  => 'Müsait',
    'gorevde' => 'Görevde'
];
?>
