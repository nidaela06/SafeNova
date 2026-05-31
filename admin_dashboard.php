<?php
/**
 * SafeNova Admin - Dashboard
 */

include 'baglanti.php';
include 'config_security.php';
include 'config_confi.php';

requireAdmin();

$stats = [];

$result = $conn->query('SELECT COUNT(*) as total FROM users WHERE is_admin = 0');
$stats['total_users'] = $result->fetch_assoc()['total'] ?? 0;

$result = $conn->query('SELECT COUNT(*) as total FROM locations WHERE DATE(created_at) = CURDATE()');
$stats['today_reports'] = $result->fetch_assoc()['total'] ?? 0;

$result = $conn->query('SELECT COUNT(*) as total FROM locations WHERE status = "bekliyor"');
$stats['pending_reports'] = $result->fetch_assoc()['total'] ?? 0;

$result = $conn->query('SELECT COUNT(*) as total FROM locations WHERE status IN ("inceleniyor", "mudahale")');
$stats['active_emergencies'] = $result->fetch_assoc()['total'] ?? 0;

$recent_logins = [];
$result = $conn->query(
    'SELECT id, ad_soyad, last_login FROM users WHERE is_admin = 0 AND last_login IS NOT NULL ORDER BY last_login DESC LIMIT 5'
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_logins[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SafeNova Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets_css_admin-style.php">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>🛡️ SafeNova</h2>
            </div>
            <nav class="admin-menu">
                <ul>
                    <li class="active"><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin.php"><i class="fas fa-cog"></i> Yönetim</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <p>👤 <?php echo htmlspecialchars($_SESSION['admin_ad_soyad'] ?? ''); ?></p>
                <a href="admin_logout.php" class="logout-btn">Çıkış Yap</a>
            </div>
        </aside>

        <div class="admin-content">
            <header class="admin-header">
                <h1>📊 Dashboard</h1>
                <p>Hoş geldin, <?php echo htmlspecialchars($_SESSION['admin_ad_soyad'] ?? ''); ?>!</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon users"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_users']; ?></h3>
                        <p>Toplam Kullanıcı</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon reports"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['today_reports']; ?></h3>
                        <p>Bugünün İhbarları</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_reports']; ?></h3>
                        <p>Bekleyen İhbarlar</p>
                    </div>
                </div>
                <div class="stat-card emergency">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['active_emergencies']; ?></h3>
                        <p style="color: #d32f2f;">🚨 Aktif Acil Durumlar</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-section">
                <h2>👥 Son Giren Kullanıcılar</h2>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Son Giriş</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_logins) > 0): ?>
                                <?php foreach ($recent_logins as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['ad_soyad']); ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($user['last_login'])); ?></td>
                                    <td><a href="admin.php?view=<?php echo $user['id']; ?>" class="btn-small">Görüntüle</a></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" style="text-align:center;color:#999;">Henüz giriş yapan kullanıcı yok</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
