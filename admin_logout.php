<?php
/**
 * SafeNova Admin - Çıkış
 */

include '../config/baglanti.php';
include '../config/security.php';

if (isset($_SESSION['admin_id'])) {
    logAdminAction($_SESSION['admin_id'], 'LOGOUT', 'Admin çıkış yapıldı');
}

session_destroy();
setcookie('admin_email', '', time() - 3600, '/');

header('Location: index.php');
exit();
?>