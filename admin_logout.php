<?php
/**
 * SafeNova Admin - Çıkış
 */

session_start();
include 'baglanti.php';
include 'config_security.php';

if (isset($_SESSION['admin_id'])) {
    logAdminAction($_SESSION['admin_id'], 'LOGOUT', 'Admin çıkış yapıldı');
}

session_destroy();
setcookie('admin_email', '', time() - 3600, '/');

header('Location: login.php');
exit();
?>
