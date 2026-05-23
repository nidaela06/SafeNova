<?php
/**
 * SafeNova - Güvenlik Fonksiyonları
 */

session_start();

// CSRF Token Oluştur
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token Kontrol
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Şifre Hash Yap
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// Şifre Doğrula
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Input Sanitize
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Admin Kontrolü
function isAdmin() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// İzin Kontrolü
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Log Kayıt
function logAdminAction($admin_id, $action, $details = '') {
    global $conn;
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $conn->prepare(
        "INSERT INTO admin_logs (admin_id, action, details, ip_address, user_agent, created_at) 
         VALUES (?, ?, ?, ?, ?, NOW())"
    );
    
    if ($stmt) {
        $stmt->bind_param('issss', $admin_id, $action, $details, $ip, $user_agent);
        $stmt->execute();
        $stmt->close();
    }
}
?>