<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Oturum gerekli']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'POST gerekli']);
    exit();
}

// JSON ve form-data her ikisini de destekle
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    // JSON değilse form-data olabilir
    $data = $_POST;
}

$phone = isset($data['phone']) ? trim($data['phone']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';
$type = isset($data['type']) ? $data['type'] : 'konum';

if (!$phone || !$message) {
    echo json_encode(['status' => 'error', 'message' => 'Telefon ve mesaj zorunlu']);
    exit();
}

// Telefon numarasını temizle (+ işareti ve boşlukları kaldır)
$phone = preg_replace('/[^0-9+]/', '', $phone);
if (strpos($phone, '0') === 0) {
    $phone = '90' . substr($phone, 1);
} elseif (strpos($phone, '+90') !== 0 && strpos($phone, '90') !== 0) {
    $phone = '90' . $phone;
}

// Mesajı URL encode yap
$message_encoded = urlencode($message);

// WhatsApp API (Twilio gibi) kullanıyorsanız:
/*
$account_sid = 'YOUR_TWILIO_SID';
$auth_token = 'YOUR_TWILIO_TOKEN';
$from_number = 'whatsapp:+1234567890';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'From' => $from_number,
        'To' => "whatsapp:+$phone",
        'Body' => $message
    ]),
    CURLOPT_USERPWD => "$account_sid:$auth_token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 201) {
    echo json_encode(['status' => 'ok', 'message' => 'WhatsApp mesajı gönderildi']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'WhatsApp gönderimi başarısız']);
}
*/

// Veya WhatsApp Web Linki (basit yöntem):
$whatsapp_url = "https://wa.me/$phone?text=$message_encoded";

// Mesajı veritabanına/dosyaya kaydet
$log_file = __DIR__ . '/whatsapp_log.json';
$log_data = [];

if (file_exists($log_file)) {
    $log_data = json_decode(file_get_contents($log_file), true) ?? [];
}

$log_entry = [
    'time' => date('Y-m-d H:i:s'),
    'phone' => $phone,
    'message' => $message,
    'type' => $type,
    'status' => 'sent',
    'url' => $whatsapp_url
];

array_unshift($log_data, $log_entry);

// Maksimum 50 kayıt tutun
if (count($log_data) > 50) {
    $log_data = array_slice($log_data, 0, 50);
}

file_put_contents($log_file, json_encode($log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode([
    'status' => 'ok',
    'message' => 'WhatsApp linki oluşturuldu',
    'url' => $whatsapp_url,
    'success' => true
]);
?>
