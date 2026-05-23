<?php
/**
 * SafeNova - Genel Konfigürasyon
 */

define('APP_NAME', 'SafeNova Admin Panel');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // production ya da development

// URL Ayarları
define('BASE_URL', 'http://localhost/safenova/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Zaman Dilimi
date_default_timezone_set('Europe/Istanbul');

// İstatistik Parametreleri
define('FAKE_REPORT_THRESHOLD', 5); // Kaç ihbar sonra şüpheli
define('ADMIN_SESSION_TIMEOUT', 3600); // 1 saat

// Sayfalama
define('ITEMS_PER_PAGE', 20);

// İhbar Durumları
$REPORT_STATUS = [
    'bekliyor' => 'Bekleniyor',
    'inceleniyor' => 'İnceleniyor',
    'mudahale' => 'Müdahale Edildi',
    'kapali' => 'Kapalı'
];

// Polis Durumları
$POLICE_STATUS = [
    'musait' => 'Müsait',
    'gorevde' => 'Görevde'
];
?>