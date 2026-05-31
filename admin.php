<?php
ob_start();
ini_set("session.save_path", "/tmp");
session_start();

// HTTPS zorla (Railway)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    exit();
}
header('X-Frame-Options: SAMEORIGIN');

include("baglanti.php");

if (!isset($_SESSION['admin_id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
        $email = trim($_POST["email"] ?? "");
        $sifre = $_POST["sifre"] ?? "";
        $stmt_a = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
        $stmt_a->bind_param("s", $email);
        $stmt_a->execute();
        $sonuc = $stmt_a->get_result();
        $stmt_a->close();
        $admin_giris_ok = false;
        if ($sonuc && $sonuc->num_rows > 0) {
            $user = $sonuc->fetch_assoc();
            if (password_verify($sifre, $user['sifre'])) {
                $admin_giris_ok = true;
            } elseif ($user['sifre'] === $sifre) {
                // Düz metin → hash yükselt
                $yh = password_hash($sifre, PASSWORD_BCRYPT);
                $upd = $conn->prepare("UPDATE users SET sifre = ? WHERE id = ?");
                $upd->bind_param("si", $yh, $user['id']);
                $upd->execute(); $upd->close();
                $admin_giris_ok = true;
            }
        }
        if ($admin_giris_ok) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_ad_soyad'] = $user['ad_soyad'];
            header("Location: admin.php");
            exit();
        } else {
            $hata = "Geçersiz admin kimlik bilgileri.";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SafeNova · Admin Girişi</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --brand:#e8315a; --brand-2:#9b1dff; --dark:#0a0a0f; --surface:#111118;
    --glass:rgba(255,255,255,0.04); --border:rgba(255,255,255,0.08);
    --text:#f0eff5; --muted:#6b6a7a;
  }
  body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); min-height:100vh; display:flex; align-items:center; justify-content:center; overflow:hidden; }
  .bg-canvas { position:fixed; inset:0; z-index:0; background:var(--dark); }
  .bg-canvas::before { content:''; position:absolute; top:-40%; left:-20%; width:700px; height:700px; background:radial-gradient(circle,rgba(232,49,90,0.18) 0%,transparent 70%); animation:drift 12s ease-in-out infinite alternate; }
  .bg-canvas::after { content:''; position:absolute; bottom:-30%; right:-10%; width:600px; height:600px; background:radial-gradient(circle,rgba(155,29,255,0.15) 0%,transparent 70%); animation:drift 14s ease-in-out infinite alternate-reverse; }
  @keyframes drift { from{transform:translate(0,0) scale(1);} to{transform:translate(40px,30px) scale(1.1);} }
  .login-wrap { position:relative; z-index:1; width:420px; animation:fadeUp 0.6s ease both; }
  @keyframes fadeUp { from{opacity:0;transform:translateY(30px);} to{opacity:1;transform:translateY(0);} }
  .login-card { background:var(--surface); border:1px solid var(--border); border-radius:24px; padding:48px 40px; box-shadow:0 32px 80px rgba(0,0,0,0.5); }
  .login-brand { display:flex; align-items:center; gap:12px; margin-bottom:36px; }
  .brand-shield { width:44px; height:44px; background:linear-gradient(135deg,var(--brand),var(--brand-2)); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 8px 24px rgba(232,49,90,0.3); }
  .brand-name { font-family:'Syne',sans-serif; font-weight:800; font-size:22px; letter-spacing:-0.5px; }
  .brand-name span { color:var(--brand); }
  .login-title { font-family:'Syne',sans-serif; font-weight:700; font-size:28px; margin-bottom:6px; letter-spacing:-0.5px; }
  .login-sub { font-size:14px; color:var(--muted); margin-bottom:32px; }
  .field { margin-bottom:16px; }
  .field label { display:block; font-size:12px; font-weight:500; color:var(--muted); margin-bottom:8px; letter-spacing:0.5px; text-transform:uppercase; }
  .field input { width:100%; padding:14px 16px; background:var(--glass); border:1px solid var(--border); border-radius:12px; color:var(--text); font-family:'DM Sans',sans-serif; font-size:15px; transition:border-color 0.2s,box-shadow 0.2s; outline:none; }
  .field input:focus { border-color:rgba(232,49,90,0.5); box-shadow:0 0 0 3px rgba(232,49,90,0.1); }
  .field input::placeholder { color:var(--muted); }
  .btn-login { width:100%; padding:15px; background:linear-gradient(135deg,var(--brand),var(--brand-2)); border:none; border-radius:12px; color:white; font-family:'Syne',sans-serif; font-size:15px; font-weight:700; cursor:pointer; margin-top:8px; transition:opacity 0.2s,transform 0.2s; box-shadow:0 8px 24px rgba(232,49,90,0.3); }
  .btn-login:hover { opacity:0.9; transform:translateY(-1px); }
  .error-box { background:rgba(232,49,90,0.1); border:1px solid rgba(232,49,90,0.3); border-radius:10px; padding:12px 16px; color:#ff7096; font-size:14px; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
  .login-note { text-align:center; margin-top:24px; font-size:12px; color:var(--muted); }
</style>
</head>
<body>
<div class="bg-canvas"></div>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-brand">
      <div class="brand-shield"><i class="fas fa-shield-halved"></i></div>
      <div class="brand-name">Safe<span>Nova</span></div>
    </div>
    <h1 class="login-title">Admin Girişi</h1>
    <p class="login-sub">Yönetim paneline erişmek için giriş yapın.</p>
    <?php if (isset($hata)): ?>
    <div class="error-box"><i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="admin_login" value="1">
      <div class="field"><label>E-posta Adresi</label><input type="email" name="email" placeholder="admin@safenova.com" required></div>
      <div class="field"><label>Şifre</label><input type="password" name="sifre" placeholder="••••••••" required></div>
      <button type="submit" class="btn-login">Giriş Yap →</button>
    </form>
    <p class="login-note"><i class="fas fa-lock" style="margin-right:5px;"></i>Bu panel yalnızca yetkili yöneticiler içindir.</p>
  </div>
</div>
</body>
</html>
    <?php
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

// Silme işlemleri
if (isset($_GET['delete_user']) && is_numeric($_GET['delete_user'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND is_admin=0");
    $stmt->bind_param("i", intval($_GET['delete_user'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=users&msg=user_deleted"); exit();
}
if (isset($_GET['delete_report']) && is_numeric($_GET['delete_report'])) {
    $stmt = $conn->prepare("DELETE FROM locations WHERE id=?");
    $stmt->bind_param("i", intval($_GET['delete_report'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=reports&msg=deleted"); exit();
}
if (isset($_GET['delete_photo']) && is_numeric($_GET['delete_photo'])) {
    $photo_id = intval($_GET['delete_photo']);
    $stmt = $conn->prepare("SELECT file_path FROM photos WHERE id=?");
    $stmt->bind_param("i", $photo_id); $stmt->execute();
    $photo = $stmt->get_result()->fetch_assoc(); $stmt->close();
    if ($photo) { $fp=$photo['file_path']; if(file_exists($fp))unlink($fp); }
    $stmt2 = $conn->prepare("DELETE FROM photos WHERE id=?");
    $stmt2->bind_param("i", $photo_id); $stmt2->execute(); $stmt2->close();
    header("Location: admin.php?action=media&msg=photo_deleted"); exit();
}
if (isset($_GET['delete_audio']) && is_numeric($_GET['delete_audio'])) {
    $audio_id = intval($_GET['delete_audio']);
    $stmt = $conn->prepare("SELECT file_path FROM audios WHERE id=?");
    $stmt->bind_param("i", $audio_id); $stmt->execute();
    $audio = $stmt->get_result()->fetch_assoc(); $stmt->close();
    if ($audio) { $fp=$audio['file_path']; if(file_exists($fp))unlink($fp); }
    $stmt2 = $conn->prepare("DELETE FROM audios WHERE id=?");
    $stmt2->bind_param("i", $audio_id); $stmt2->execute(); $stmt2->close();
    header("Location: admin.php?action=media&msg=audio_deleted"); exit();
}
if (isset($_GET['delete_contact']) && is_numeric($_GET['delete_contact'])) {
    $stmt = $conn->prepare("DELETE FROM acil_kisiler WHERE id=?");
    $stmt->bind_param("i", intval($_GET['delete_contact'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=contacts&msg=deleted"); exit();
}
if (isset($_GET['delete_call']) && is_numeric($_GET['delete_call'])) {
    $stmt = $conn->prepare("DELETE FROM sent_logs WHERE id=?");
    $stmt->bind_param("i", intval($_GET['delete_call'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=calls&msg=deleted"); exit();
}
if (isset($_GET['delete_user_calls']) && is_numeric($_GET['delete_user_calls'])) {
    $stmt = $conn->prepare("DELETE FROM sent_logs WHERE user_id=?");
    $stmt->bind_param("i", intval($_GET['delete_user_calls'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=calls&msg=deleted"); exit();
}
if (isset($_GET['delete_all_calls']) && $_GET['delete_all_calls'] === '1') {
    $conn->query("DELETE FROM sent_logs");
    header("Location: admin.php?action=calls&msg=deleted"); exit();
}
// Buluşma silme
if (isset($_GET['delete_bulusma']) && is_numeric($_GET['delete_bulusma'])) {
    $stmt = $conn->prepare("DELETE FROM bulusmalar WHERE id=?");
    $stmt->bind_param("i", intval($_GET['delete_bulusma'])); $stmt->execute(); $stmt->close();
    header("Location: admin.php?action=bulusmalar&msg=deleted"); exit();
}
if ($action == 'logout') { session_destroy(); header("Location: admin.php"); exit(); }

// İstatistikler
$toplam_users    = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$toplam_reports  = $conn->query("SELECT COUNT(*) as total FROM locations")->fetch_assoc()['total'];
$toplam_photos   = $conn->query("SELECT COUNT(*) as total FROM photos")->fetch_assoc()['total'];
$toplam_audios   = $conn->query("SELECT COUNT(*) as total FROM audios")->fetch_assoc()['total'];
$toplam_contacts = $conn->query("SELECT COUNT(*) as total FROM acil_kisiler")->fetch_assoc()['total'];
$r_calls = $conn->query("SELECT COUNT(*) as total FROM sent_logs");
$toplam_calls    = $r_calls ? $r_calls->fetch_assoc()['total'] : 0;
$r_bulusma = $conn->query("SELECT COUNT(*) as total FROM bulusmalar");
$toplam_bulusmalar = $r_bulusma ? $r_bulusma->fetch_assoc()['total'] : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SafeNova · Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --brand:#e8315a; --brand-2:#9b1dff; --brand-glow:rgba(232,49,90,0.25);
      --dark:#08080d; --surface:#101017; --surface-2:#16161f; --surface-3:#1d1d28;
      --glass:rgba(255,255,255,0.03); --border:rgba(255,255,255,0.07); --border-2:rgba(255,255,255,0.12);
      --text:#eeedf5; --text-2:#a09db8; --muted:#5c5a6e;
      --success:#22c55e; --warning:#f59e0b; --danger:#ef4444; --info:#3b82f6;
      --sidebar-w:260px; --topbar-h:64px; --radius:14px; --radius-sm:8px;
    }
    html,body { height:100%; }
    body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); overflow-x:hidden; }
    .layout { display:flex; min-height:100vh; }

    /* SIDEBAR */
    .sidebar { width:var(--sidebar-w); background:var(--surface); border-right:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; z-index:100; }
    .sidebar-head { padding:24px 20px; border-bottom:1px solid var(--border); }
    .sidebar-brand { display:flex; align-items:center; gap:11px; }
    .brand-icon { width:38px; height:38px; background:linear-gradient(135deg,var(--brand),var(--brand-2)); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; color:white; box-shadow:0 6px 18px var(--brand-glow); flex-shrink:0; }
    .brand-text { font-family:'Syne',sans-serif; font-weight:800; font-size:18px; letter-spacing:-0.3px; }
    .brand-text em { color:var(--brand); font-style:normal; }
    .brand-badge { font-size:10px; color:var(--muted); font-weight:400; letter-spacing:0.5px; margin-top:1px; display:block; }
    .sidebar-nav { flex:1; padding:16px 12px; overflow-y:auto; }
    .nav-section-label { font-size:10px; letter-spacing:1.2px; text-transform:uppercase; color:var(--muted); font-weight:500; padding:12px 10px 6px; }
    .nav-item { display:flex; align-items:center; gap:10px; padding:11px 12px; border-radius:var(--radius-sm); color:var(--text-2); text-decoration:none; font-size:14px; font-weight:400; margin-bottom:2px; transition:background 0.15s,color 0.15s; position:relative; }
    .nav-item i { width:18px; text-align:center; font-size:14px; opacity:0.8; }
    .nav-item:hover { background:var(--glass); color:var(--text); }
    .nav-item.active { background:rgba(232,49,90,0.12); color:var(--brand); font-weight:500; }
    .nav-item.active i { opacity:1; }
    .nav-item.active::before { content:''; position:absolute; left:0; top:20%; bottom:20%; width:3px; background:var(--brand); border-radius:0 2px 2px 0; }
    .nav-badge { margin-left:auto; background:var(--surface-3); color:var(--text-2); font-size:10px; font-weight:600; padding:2px 7px; border-radius:20px; min-width:20px; text-align:center; }
    .nav-item.active .nav-badge { background:rgba(232,49,90,0.2); color:var(--brand); }
    .sidebar-foot { padding:16px 12px; border-top:1px solid var(--border); }
    .sidebar-user { display:flex; align-items:center; gap:10px; padding:10px; border-radius:var(--radius-sm); background:var(--glass); border:1px solid var(--border); }
    .user-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand-2)); display:flex; align-items:center; justify-content:center; font-size:14px; color:white; font-family:'Syne',sans-serif; font-weight:700; flex-shrink:0; }
    .user-info { flex:1; min-width:0; }
    .user-name { font-size:13px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .user-role { font-size:11px; color:var(--muted); }
    .logout-link { color:var(--muted); font-size:16px; text-decoration:none; transition:color 0.2s; }
    .logout-link:hover { color:var(--danger); }

    /* MAIN */
    .main { margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; min-height:100vh; }
    .topbar { height:var(--topbar-h); border-bottom:1px solid var(--border); padding:0 32px; display:flex; align-items:center; justify-content:space-between; background:var(--surface); position:sticky; top:0; z-index:50; }
    .topbar-left { display:flex; align-items:center; gap:12px; }
    .page-title { font-family:'Syne',sans-serif; font-weight:700; font-size:18px; letter-spacing:-0.3px; }
    .page-breadcrumb { font-size:13px; color:var(--muted); }
    .topbar-right { display:flex; align-items:center; gap:12px; }
    .topbar-time { font-size:13px; color:var(--muted); }
    .topbar-dot { width:8px; height:8px; border-radius:50%; background:var(--success); box-shadow:0 0 0 3px rgba(34,197,94,0.2); animation:pulse 2s ease infinite; }
    @keyframes pulse { 0%,100%{box-shadow:0 0 0 3px rgba(34,197,94,0.2);} 50%{box-shadow:0 0 0 6px rgba(34,197,94,0.05);} }
    .content { padding:32px; flex:1; }

    /* ALERTS */
    .alert { display:flex; align-items:center; gap:10px; padding:14px 18px; border-radius:var(--radius-sm); font-size:14px; margin-bottom:24px; animation:fadeUp 0.3s ease; }
    .alert-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); color:#86efac; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(-8px);} to{opacity:1;transform:translateY(0);} }

    /* SECTION HEADER */
    .section-header { margin-bottom:28px; }
    .section-header h1 { font-family:'Syne',sans-serif; font-weight:700; font-size:26px; letter-spacing:-0.5px; margin-bottom:4px; }
    .section-header p { font-size:14px; color:var(--text-2); }

    /* STAT CARDS */
    .stats-row { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:32px; }
    .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:22px 20px; transition:border-color 0.2s,transform 0.2s; cursor:default; position:relative; overflow:hidden; }
    .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--stat-color,var(--brand)); opacity:0; transition:opacity 0.2s; }
    .stat-card:hover { border-color:var(--border-2); transform:translateY(-2px); }
    .stat-card:hover::before { opacity:1; }
    .stat-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
    .stat-icon-wrap { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; }
    .stat-num { font-family:'Syne',sans-serif; font-size:30px; font-weight:800; letter-spacing:-1px; line-height:1; }
    .stat-label { font-size:13px; color:var(--text-2); margin-top:4px; }
    .stat-users{--stat-color:#818cf8;} .stat-contacts{--stat-color:#34d399;} .stat-reports{--stat-color:#f87171;} .stat-photos{--stat-color:#60a5fa;} .stat-audios{--stat-color:#a78bfa;} .stat-bulusma{--stat-color:#fb923c;}
    .si-users{background:rgba(129,140,248,0.15);color:#818cf8;} .si-contacts{background:rgba(52,211,153,0.15);color:#34d399;} .si-reports{background:rgba(248,113,113,0.15);color:#f87171;} .si-photos{background:rgba(96,165,250,0.15);color:#60a5fa;} .si-audios{background:rgba(167,139,250,0.15);color:#a78bfa;} .si-bulusma{background:rgba(251,146,60,0.15);color:#fb923c;}

    /* CARDS */
    .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; margin-bottom:20px; }
    .card-head { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
    .card-title { font-family:'Syne',sans-serif; font-weight:700; font-size:15px; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--brand); font-size:14px; }
    .card-count { font-size:12px; color:var(--muted); background:var(--surface-3); padding:4px 10px; border-radius:20px; }

    /* TABLE */
    .table-wrap { overflow-x:auto; }
    table.data-table { width:100%; border-collapse:collapse; }
    table.data-table thead tr { background:var(--surface-2); }
    table.data-table th { padding:12px 20px; text-align:left; font-size:11px; font-weight:600; letter-spacing:0.8px; text-transform:uppercase; color:var(--muted); white-space:nowrap; }
    table.data-table td { padding:14px 20px; border-bottom:1px solid var(--border); font-size:14px; vertical-align:middle; }
    table.data-table tbody tr:last-child td { border-bottom:none; }
    table.data-table tbody tr:hover { background:var(--glass); }
    .cell-id { font-size:12px; color:var(--muted); font-family:monospace; }
    .cell-name { font-weight:500; }
    .cell-email { font-size:13px; color:var(--text-2); }
    .cell-date { font-size:12px; color:var(--muted); white-space:nowrap; }
    .cell-sub { font-size:12px; color:var(--muted); display:block; }

    .map-link { display:inline-flex; align-items:center; gap:5px; color:var(--info); font-size:13px; text-decoration:none; background:rgba(59,130,246,0.1); padding:4px 10px; border-radius:6px; transition:background 0.15s; }
    .map-link:hover { background:rgba(59,130,246,0.2); }

    .btn-delete { display:inline-flex; align-items:center; gap:6px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#f87171; padding:7px 13px; border-radius:var(--radius-sm); font-size:12px; font-weight:500; text-decoration:none; cursor:pointer; transition:background 0.15s,border-color 0.15s; font-family:'DM Sans',sans-serif; }
    .btn-delete:hover { background:rgba(239,68,68,0.2); border-color:rgba(239,68,68,0.4); color:#fca5a5; }

    .btn-view { display:inline-flex; align-items:center; gap:6px; background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.2); color:#60a5fa; padding:7px 13px; border-radius:var(--radius-sm); font-size:12px; font-weight:500; text-decoration:none; cursor:pointer; transition:background 0.15s; font-family:'DM Sans',sans-serif; }
    .btn-view:hover { background:rgba(59,130,246,0.2); }

    .empty-state { text-align:center; padding:48px 20px; color:var(--muted); }
    .empty-state i { font-size:32px; margin-bottom:12px; display:block; opacity:0.4; }
    .empty-state p { font-size:14px; }

    /* DASHBOARD */
    .dash-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }

    /* MEDIA TABS */
    .media-tabs { display:flex; gap:4px; padding:16px 24px; border-bottom:1px solid var(--border); background:var(--surface-2); }
    .tab-btn { padding:8px 16px; border-radius:var(--radius-sm); font-size:13px; font-weight:500; cursor:pointer; border:none; background:transparent; color:var(--text-2); font-family:'DM Sans',sans-serif; transition:background 0.15s,color 0.15s; }
    .tab-btn.active { background:var(--surface-3); color:var(--text); }
    .tab-panel { display:none; padding:24px; }
    .tab-panel.active { display:block; }
    .photo-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:16px; }
    .photo-item { border-radius:var(--radius-sm); overflow:hidden; background:var(--surface-2); border:1px solid var(--border); transition:transform 0.2s,border-color 0.2s; }
    .photo-item:hover { transform:translateY(-3px); border-color:var(--border-2); }
    .photo-item img { width:100%; height:140px; object-fit:cover; display:block; }
    .photo-meta { padding:10px 12px; }
    .photo-meta .pname { font-size:13px; font-weight:500; }
    .photo-meta .pdate { font-size:11px; color:var(--muted); margin:3px 0 8px; }
    .audio-list-wrap { display:flex; flex-direction:column; gap:12px; }
    .audio-row { display:flex; align-items:center; gap:14px; padding:14px 18px; background:var(--surface-2); border:1px solid var(--border); border-radius:var(--radius-sm); border-left:3px solid var(--brand); }
    .audio-icon { width:40px; height:40px; border-radius:10px; background:rgba(232,49,90,0.1); display:flex; align-items:center; justify-content:center; color:var(--brand); font-size:16px; flex-shrink:0; }
    .audio-meta { min-width:180px; }
    .audio-meta .aname { font-size:13px; font-weight:500; }
    .audio-meta .adate { font-size:11px; color:var(--muted); margin-top:2px; }
    .audio-player { flex:1; }
    audio { width:100%; height:36px; }

    /* BULUŞMA ÖZEL STİLLER */
    .bulusma-badge {
      display:inline-flex; align-items:center; gap:4px;
      padding:3px 10px; border-radius:20px;
      font-size:12px; font-weight:600;
    }
    .bulusma-detail-modal {
      display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7);
      backdrop-filter:blur(4px); z-index:999;
      align-items:center; justify-content:center;
    }
    .bulusma-detail-modal.show { display:flex; }
    .bulusma-detail-box {
      background:var(--surface); border:1px solid var(--border-2);
      border-radius:20px; width:520px; max-width:95vw;
      max-height:90vh; overflow-y:auto;
      box-shadow:0 40px 80px rgba(0,0,0,0.5);
    }
    .bulusma-detail-head {
      padding:20px 24px; border-bottom:1px solid var(--border);
      display:flex; align-items:center; justify-content:space-between;
    }
    .bulusma-detail-body { padding:24px; display:flex; flex-direction:column; gap:14px; }
    .detail-row {
      display:flex; align-items:flex-start; gap:12px;
      padding:12px 14px; background:var(--surface-2);
      border:1px solid var(--border); border-radius:10px;
    }
    .detail-row i { color:var(--brand); margin-top:2px; width:16px; flex-shrink:0; }
    .detail-row-content .dlabel { font-size:11px; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
    .detail-row-content .dval { font-size:14px; color:var(--text); margin-top:3px; }
    .suphe-box { background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2); border-radius:10px; padding:14px; }
    .suphe-box .suphe-label { font-size:11px; color:#f59e0b; font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:6px; }
    .suphe-box .suphe-text { font-size:13px; color:var(--text-2); line-height:1.6; }
    .close-modal-btn { background:var(--surface-3); border:none; color:var(--text-2); width:32px; height:32px; border-radius:8px; cursor:pointer; font-size:16px; transition:background 0.2s; }
    .close-modal-btn:hover { background:var(--surface-2); color:var(--text); }

    /* RESPONSIVE */
    @media(max-width:1280px) { .stats-row{grid-template-columns:repeat(3,1fr);} .dash-grid{grid-template-columns:1fr;} }
    @media(max-width:900px) { .stats-row{grid-template-columns:repeat(2,1fr);} .sidebar{transform:translateX(-100%);} .main{margin-left:0;} }
    @media(max-width:540px) { .stats-row{grid-template-columns:1fr;} .content{padding:20px;} .topbar{padding:0 20px;} }
  </style>
</head>
<body>
<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-head">
      <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-shield-halved"></i></div>
        <div>
          <div class="brand-text">Safe<em>Nova</em></div>
          <span class="brand-badge">Admin Paneli</span>
        </div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Genel</div>
      <a class="nav-item <?= $action=='dashboard'?'active':'' ?>" href="admin.php?action=dashboard">
        <i class="fas fa-chart-pie"></i> Dashboard
      </a>
      <div class="nav-section-label" style="margin-top:8px;">Yönetim</div>
      <a class="nav-item <?= $action=='users'?'active':'' ?>" href="admin.php?action=users">
        <i class="fas fa-users"></i> Kullanıcılar
        <span class="nav-badge"><?= $toplam_users ?></span>
      </a>
      <a class="nav-item <?= $action=='contacts'?'active':'' ?>" href="admin.php?action=contacts">
        <i class="fas fa-address-book"></i> Acil Kişiler
        <span class="nav-badge"><?= $toplam_contacts ?></span>
      </a>
      <a class="nav-item <?= $action=='reports'?'active':'' ?>" href="admin.php?action=reports">
        <i class="fas fa-triangle-exclamation"></i> İhbarlar
        <span class="nav-badge"><?= $toplam_reports ?></span>
      </a>
      <a class="nav-item <?= $action=='media'?'active':'' ?>" href="admin.php?action=media">
        <i class="fas fa-photo-film"></i> Medya
        <span class="nav-badge"><?= ($toplam_photos + $toplam_audios) ?></span>
      </a>
      <a class="nav-item <?= $action=='bulusmalar'?'active':'' ?>" href="admin.php?action=bulusmalar">
        <i class="fas fa-calendar-alt"></i> Buluşma Takibi
        <span class="nav-badge" style="<?= $toplam_bulusmalar>0?'background:rgba(251,146,60,0.2);color:#fb923c;':'' ?>"><?= $toplam_bulusmalar ?></span>
      </a>
      <div class="nav-section-label" style="margin-top:8px;">Güvenlik</div>
      <a class="nav-item <?= $action=='calls'?'active':'' ?>" href="admin.php?action=calls">
        <i class="fas fa-shield-halved"></i> Polis &amp; Acil Çağrılar
        <?php if ($toplam_calls > 0): ?>
        <span class="nav-badge" style="background:rgba(239,68,68,0.2);color:#f87171;"><?= $toplam_calls ?></span>
        <?php endif; ?>
      </a>
    </nav>
    <div class="sidebar-foot">
      <div class="sidebar-user">
        <div class="user-avatar"><?= mb_strtoupper(mb_substr($_SESSION['admin_ad_soyad'],0,1)) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($_SESSION['admin_ad_soyad']) ?></div>
          <div class="user-role">Süper Admin</div>
        </div>
        <a href="admin.php?action=logout" class="logout-link" title="Çıkış Yap"><i class="fas fa-arrow-right-from-bracket"></i></a>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-left">
        <div class="page-title">
          <?php
          $titles = ['dashboard'=>'Dashboard','users'=>'Kullanıcılar','contacts'=>'Acil Kişiler','reports'=>'İhbarlar','media'=>'Medya','calls'=>'Polis & Acil Çağrılar','bulusmalar'=>'Buluşma Takibi'];
          echo $titles[$action] ?? 'Dashboard';
          ?>
        </div>
        <span class="page-breadcrumb">/ SafeNova</span>
      </div>
      <div class="topbar-right">
        <span class="topbar-time" id="clock"></span>
        <div class="topbar-dot" title="Sistem aktif"></div>
      </div>
    </div>

    <div class="content">
      <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success"><i class="fas fa-circle-check"></i> İşlem başarıyla tamamlandı.</div>
      <?php endif; ?>

      <!-- ══ DASHBOARD ══ -->
      <?php if ($action == 'dashboard'):
        $son_users   = $conn->query("SELECT ad_soyad,email,created_at FROM users WHERE is_admin=0 ORDER BY created_at DESC LIMIT 5");
        $son_reports = $conn->query("SELECT l.created_at,u.ad_soyad FROM locations l JOIN users u ON l.user_id=u.id ORDER BY l.created_at DESC LIMIT 5");
        $son_medya   = $conn->query("SELECT 'foto' as tip,p.file_path,p.created_at,u.ad_soyad FROM photos p JOIN users u ON p.user_id=u.id UNION ALL SELECT 'ses',a.file_path,a.created_at,u.ad_soyad FROM audios a JOIN users u ON a.user_id=u.id ORDER BY created_at DESC LIMIT 5");
        $son_bulusma = $conn->query("SELECT b.kisi_adi,b.bulusma_tarihi,b.bulusma_saati,b.yakinlik,u.ad_soyad FROM bulusmalar b JOIN users u ON b.user_id=u.id ORDER BY b.created_at DESC LIMIT 5");
      ?>
        <div class="section-header">
          <h1>Hoş geldin, <?= htmlspecialchars(explode(' ',$_SESSION['admin_ad_soyad'])[0]) ?> 👋</h1>
          <p>Sistemin genel durumuna buradan göz atabilirsiniz.</p>
        </div>
        <div class="stats-row">
          <div class="stat-card stat-users"><div class="stat-row"><div class="stat-icon-wrap si-users"><i class="fas fa-users"></i></div></div><div class="stat-num"><?= $toplam_users ?></div><div class="stat-label">Toplam Kullanıcı</div></div>
          <div class="stat-card stat-contacts"><div class="stat-row"><div class="stat-icon-wrap si-contacts"><i class="fas fa-address-book"></i></div></div><div class="stat-num"><?= $toplam_contacts ?></div><div class="stat-label">Acil Kişi</div></div>
          <div class="stat-card stat-reports"><div class="stat-row"><div class="stat-icon-wrap si-reports"><i class="fas fa-triangle-exclamation"></i></div></div><div class="stat-num"><?= $toplam_reports ?></div><div class="stat-label">İhbar Kaydı</div></div>
          <div class="stat-card stat-photos"><div class="stat-row"><div class="stat-icon-wrap si-photos"><i class="fas fa-camera"></i></div></div><div class="stat-num"><?= $toplam_photos ?></div><div class="stat-label">Fotoğraf</div></div>
          <div class="stat-card stat-bulusma"><div class="stat-row"><div class="stat-icon-wrap si-bulusma"><i class="fas fa-calendar-alt"></i></div></div><div class="stat-num"><?= $toplam_bulusmalar ?></div><div class="stat-label">Buluşma Kaydı</div></div>
        </div>
        <div class="dash-grid">
          <div class="card">
            <div class="card-head">
              <div class="card-title"><i class="fas fa-user-plus"></i> Son Kayıtlar</div>
              <a href="admin.php?action=users" style="font-size:12px;color:var(--brand);text-decoration:none;">Tümünü gör →</a>
            </div>
            <div class="table-wrap"><table class="data-table"><thead><tr><th>Ad Soyad</th><th>E-posta</th><th>Tarih</th></tr></thead><tbody>
            <?php $has=false; while($u=$son_users->fetch_assoc()): $has=true; ?>
              <tr><td class="cell-name"><?= htmlspecialchars($u['ad_soyad']) ?></td><td class="cell-email"><?= htmlspecialchars($u['email']) ?></td><td class="cell-date"><?= date('d.m.Y H:i',strtotime($u['created_at'])) ?></td></tr>
            <?php endwhile; if(!$has): ?><tr><td colspan="3"><div class="empty-state"><i class="fas fa-inbox"></i><p>Kayıt yok.</p></div></td></tr><?php endif; ?>
            </tbody></table></div>
          </div>
          <div class="card">
            <div class="card-head">
              <div class="card-title"><i class="fas fa-triangle-exclamation"></i> Son İhbarlar</div>
              <a href="admin.php?action=reports" style="font-size:12px;color:var(--brand);text-decoration:none;">Tümünü gör →</a>
            </div>
            <div class="table-wrap"><table class="data-table"><thead><tr><th>Kullanıcı</th><th>Tarih</th></tr></thead><tbody>
            <?php $has2=false; while($r=$son_reports->fetch_assoc()): $has2=true; ?>
              <tr><td class="cell-name"><?= htmlspecialchars($r['ad_soyad']) ?></td><td class="cell-date"><?= date('d.m.Y H:i',strtotime($r['created_at'])) ?></td></tr>
            <?php endwhile; if(!$has2): ?><tr><td colspan="2"><div class="empty-state"><i class="fas fa-inbox"></i><p>İhbar yok.</p></div></td></tr><?php endif; ?>
            </tbody></table></div>
          </div>
        </div>

        <!-- Son Buluşmalar dashboard widget -->
        <div class="card" style="margin-bottom:20px;">
          <div class="card-head">
            <div class="card-title"><i class="fas fa-calendar-alt"></i> Son Buluşma Kayıtları</div>
            <a href="admin.php?action=bulusmalar" style="font-size:12px;color:var(--brand);text-decoration:none;">Tümünü gör →</a>
          </div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>Kullanıcı</th><th>Kişi Adı</th><th>Yakınlık</th><th>Tarih & Saat</th></tr></thead><tbody>
          <?php
          $has_b = false;
          if ($son_bulusma) while($b=$son_bulusma->fetch_assoc()): $has_b=true;
            $yakinlik_renk = ['Arkadaş'=>'#60a5fa','Aile'=>'#34d399','İş Arkadaşı'=>'#fb923c','Tanıdık'=>'#a78bfa','Yabancı'=>'#f87171','Diğer'=>'#6b7280'];
            $renk = $yakinlik_renk[$b['yakinlik']] ?? '#6b7280';
          ?>
            <tr>
              <td class="cell-name"><?= htmlspecialchars($b['ad_soyad']) ?></td>
              <td class="cell-name"><?= htmlspecialchars($b['kisi_adi']) ?></td>
              <td><?php if($b['yakinlik']): ?><span class="bulusma-badge" style="background:<?= $renk ?>22;color:<?= $renk ?>;"><?= htmlspecialchars($b['yakinlik']) ?></span><?php else: ?>—<?php endif; ?></td>
              <td class="cell-date"><?= $b['bulusma_tarihi'] ? date('d.m.Y',strtotime($b['bulusma_tarihi'])).' '.substr($b['bulusma_saati']??'',0,5) : '—' ?></td>
            </tr>
          <?php endwhile; if(!$has_b): ?><tr><td colspan="4"><div class="empty-state"><i class="fas fa-calendar-times"></i><p>Buluşma kaydı yok.</p></div></td></tr><?php endif; ?>
          </tbody></table></div>
        </div>

        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="fas fa-photo-film"></i> Son Medya Yüklemeleri</div>
            <a href="admin.php?action=media" style="font-size:12px;color:var(--brand);text-decoration:none;">Tümünü gör →</a>
          </div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>Tür</th><th>Kullanıcı</th><th>Dosya</th><th>Tarih</th></tr></thead><tbody>
          <?php $has3=false; while($m=$son_medya->fetch_assoc()): $has3=true; ?>
            <tr>
              <td><?php if($m['tip']=='foto'): ?><span style="background:rgba(96,165,250,0.1);color:#60a5fa;padding:3px 10px;border-radius:20px;font-size:12px;"><i class="fas fa-camera" style="margin-right:4px;"></i>Fotoğraf</span><?php else: ?><span style="background:rgba(167,139,250,0.1);color:#a78bfa;padding:3px 10px;border-radius:20px;font-size:12px;"><i class="fas fa-microphone" style="margin-right:4px;"></i>Ses</span><?php endif; ?></td>
              <td class="cell-name"><?= htmlspecialchars($m['ad_soyad']) ?></td>
              <td style="font-size:12px;color:var(--muted);font-family:monospace;"><?= htmlspecialchars(basename($m['file_path'])) ?></td>
              <td class="cell-date"><?= date('d.m.Y H:i',strtotime($m['created_at'])) ?></td>
            </tr>
          <?php endwhile; if(!$has3): ?><tr><td colspan="4"><div class="empty-state"><i class="fas fa-inbox"></i><p>Medya yok.</p></div></td></tr><?php endif; ?>
          </tbody></table></div>
        </div>

      <!-- ══ KULLANICILAR ══ -->
      <?php elseif ($action == 'users'):
        $result = $conn->query("SELECT id,ad_soyad,email,phone,dogum_tarihi,kan_grubu,kronik_hastalik,ilac_kullanimi,alerji,created_at FROM users WHERE is_admin=0 ORDER BY created_at DESC");
        $users=[]; while($row=$result->fetch_assoc()) $users[]=$row;
      ?>
        <div id="user-modal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
          <div style="background:var(--surface);border:1px solid var(--border-2);border-radius:20px;width:560px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 40px 80px rgba(0,0,0,0.5);">
            <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
              <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;display:flex;align-items:center;gap:8px;"><i class="fas fa-user-circle" style="color:var(--brand);"></i><span id="modal-name">Kullanıcı Detayı</span></div>
              <button onclick="closeModal()" style="background:var(--surface-3);border:none;color:var(--text-2);width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:16px;">✕</button>
            </div>
            <div style="padding:24px;">
              <div style="margin-bottom:20px;">
                <div style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:12px;">Temel Bilgiler</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                  <div class="detail-box"><span class="detail-label">E-posta</span><span class="detail-val" id="m-email">—</span></div>
                  <div class="detail-box"><span class="detail-label">Telefon</span><span class="detail-val" id="m-phone">—</span></div>
                  <div class="detail-box"><span class="detail-label">Kayıt Tarihi</span><span class="detail-val" id="m-date">—</span></div>
                  <div class="detail-box"><span class="detail-label">Doğum Tarihi</span><span class="detail-val" id="m-dob">—</span></div>
                </div>
              </div>
              <div>
                <div style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:12px;display:flex;align-items:center;gap:6px;"><i class="fas fa-heart-pulse" style="color:#f87171;"></i> Sağlık Bilgileri</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                  <div class="detail-box detail-health"><span class="detail-label"><i class="fas fa-droplet" style="color:#f87171;margin-right:4px;"></i>Kan Grubu</span><span class="detail-val" id="m-kan" style="font-size:20px;font-family:'Syne',sans-serif;font-weight:800;color:#f87171;">—</span></div>
                  <div class="detail-box detail-health"><span class="detail-label"><i class="fas fa-stethoscope" style="color:#60a5fa;margin-right:4px;"></i>Kronik Hastalık</span><span class="detail-val" id="m-kronik">—</span></div>
                  <div class="detail-box detail-health"><span class="detail-label"><i class="fas fa-pills" style="color:#a78bfa;margin-right:4px;"></i>İlaç Kullanımı</span><span class="detail-val" id="m-ilac">—</span></div>
                  <div class="detail-box detail-health"><span class="detail-label"><i class="fas fa-triangle-exclamation" style="color:#f59e0b;margin-right:4px;"></i>Alerji</span><span class="detail-val" id="m-alerji">—</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style>
          .detail-box{background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;display:flex;flex-direction:column;gap:4px;}
          .detail-label{font-size:11px;color:var(--muted);font-weight:500;}
          .detail-val{font-size:14px;color:var(--text);font-weight:400;}
          .detail-health{border-color:rgba(248,113,113,0.15);}
          .btn-detail{display:inline-flex;align-items:center;gap:5px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);color:#818cf8;padding:7px 12px;border-radius:var(--radius-sm);font-size:12px;font-weight:500;cursor:pointer;transition:background 0.15s;font-family:'DM Sans',sans-serif;text-decoration:none;}
          .btn-detail:hover{background:rgba(99,102,241,0.2);}
          .action-group{display:flex;gap:6px;align-items:center;}
        </style>
        <div class="section-header"><h1>Kullanıcı Yönetimi</h1><p>Toplam <?= count($users) ?> kullanıcı kayıtlı.</p></div>
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="fas fa-users"></i> Tüm Kullanıcılar</div><span class="card-count"><?= count($users) ?> kayıt</span></div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>#</th><th>Ad Soyad</th><th>E-posta</th><th>Kan Grubu</th><th>Kayıt Tarihi</th><th>İşlem</th></tr></thead><tbody>
          <?php if(count($users)==0): ?><tr><td colspan="6"><div class="empty-state"><i class="fas fa-users-slash"></i><p>Kayıtlı kullanıcı yok.</p></div></td></tr>
          <?php else: foreach($users as $u): $kan=htmlspecialchars($u['kan_grubu']??''); ?>
            <tr>
              <td class="cell-id">#<?= $u['id'] ?></td>
              <td class="cell-name"><?= htmlspecialchars($u['ad_soyad']) ?></td>
              <td class="cell-email"><?= htmlspecialchars($u['email']) ?></td>
              <td><?php if($kan): ?><span style="background:rgba(248,113,113,0.15);color:#f87171;padding:3px 10px;border-radius:20px;font-size:13px;font-weight:700;font-family:'Syne',sans-serif;"><i class="fas fa-droplet" style="font-size:10px;margin-right:3px;"></i><?= $kan ?></span><?php else: ?><span style="color:var(--muted);font-size:12px;">—</span><?php endif; ?></td>
              <td class="cell-date"><?= date('d.m.Y',strtotime($u['created_at'])) ?></td>
              <td><div class="action-group"><button class="btn-detail" onclick="openModal(<?= htmlspecialchars(json_encode($u),ENT_QUOTES) ?>)"><i class="fas fa-eye"></i> Detay</button><a href="admin.php?action=users&delete_user=<?= $u['id'] ?>" class="btn-delete" onclick="return confirm('<?= addslashes(htmlspecialchars($u['ad_soyad'])) ?> silinsin mi?')"><i class="fas fa-trash-alt"></i> Sil</a></div></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </div>
        <script>
        function openModal(u){
          document.getElementById('modal-name').textContent=u.ad_soyad||'—';
          document.getElementById('m-email').textContent=u.email||'—';
          document.getElementById('m-phone').textContent=u.phone||'—';
          document.getElementById('m-date').textContent=u.created_at?u.created_at.substring(0,10):'—';
          document.getElementById('m-dob').textContent=u.dogum_tarihi||'—';
          document.getElementById('m-kan').textContent=u.kan_grubu||'—';
          document.getElementById('m-kronik').textContent=u.kronik_hastalik||'Yok';
          document.getElementById('m-ilac').textContent=u.ilac_kullanimi||'Yok';
          document.getElementById('m-alerji').textContent=u.alerji||'Yok';
          document.getElementById('user-modal').style.display='flex';
        }
        function closeModal(){ document.getElementById('user-modal').style.display='none'; }
        document.getElementById('user-modal').addEventListener('click',function(e){ if(e.target===this)closeModal(); });
        </script>

      <!-- ══ ACİL KİŞİLER ══ -->
      <?php elseif ($action == 'contacts'):
        $result=$conn->query("SELECT ak.id,ak.ad_soyad,ak.telefon,ak.yakinlik,ak.created_at,u.ad_soyad as kullanici_adi,u.email FROM acil_kisiler ak JOIN users u ON ak.user_id=u.id ORDER BY ak.created_at DESC");
        $contacts=[]; while($row=$result->fetch_assoc()) $contacts[]=$row;
      ?>
        <div class="section-header"><h1>Acil Durum Kişileri</h1><p>Toplam <?= count($contacts) ?> acil kişi tanımlı.</p></div>
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="fas fa-address-book"></i> Acil Kişiler</div><span class="card-count"><?= count($contacts) ?> kayıt</span></div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>#</th><th>Kişi Adı</th><th>Telefon</th><th>Yakınlık</th><th>Kullanıcı</th><th>Tarih</th><th>İşlem</th></tr></thead><tbody>
          <?php if(count($contacts)==0): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-user-slash"></i><p>Acil kişi bulunmuyor.</p></div></td></tr>
          <?php else: foreach($contacts as $c): ?>
            <tr>
              <td class="cell-id">#<?= $c['id'] ?></td>
              <td class="cell-name"><?= htmlspecialchars($c['ad_soyad']) ?></td>
              <td><?= htmlspecialchars($c['telefon']) ?></td>
              <td><span style="background:var(--surface-3);padding:3px 9px;border-radius:20px;font-size:12px;"><?= htmlspecialchars($c['yakinlik']??'—') ?></span></td>
              <td><span class="cell-name"><?= htmlspecialchars($c['kullanici_adi']) ?></span><span class="cell-sub"><?= htmlspecialchars($c['email']) ?></span></td>
              <td class="cell-date"><?= date('d.m.Y H:i',strtotime($c['created_at'])) ?></td>
              <td><a href="admin.php?action=contacts&delete_contact=<?= $c['id'] ?>" class="btn-delete" onclick="return confirm('Kişiyi silmek istiyor musun?')"><i class="fas fa-trash-alt"></i> Sil</a></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </div>

      <!-- ══ İHBARLAR ══ -->
      <?php elseif ($action == 'reports'):
        $result=$conn->query("SELECT l.id,l.lat,l.lng,l.created_at,u.ad_soyad,u.email FROM locations l JOIN users u ON l.user_id=u.id ORDER BY l.created_at DESC");
        $reports=[]; while($row=$result->fetch_assoc()) $reports[]=$row;
      ?>
        <div class="section-header"><h1>İhbar Yönetimi</h1><p>Toplam <?= count($reports) ?> ihbar kaydı.</p></div>
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="fas fa-triangle-exclamation"></i> Tüm İhbarlar</div><span class="card-count"><?= count($reports) ?> kayıt</span></div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>#</th><th>Kullanıcı</th><th>E-posta</th><th>Konum</th><th>Tarih</th><th>İşlem</th></tr></thead><tbody>
          <?php if(count($reports)==0): ?><tr><td colspan="6"><div class="empty-state"><i class="fas fa-check-circle"></i><p>Hiç ihbar bulunmuyor.</p></div></td></tr>
          <?php else: foreach($reports as $r): ?>
            <tr>
              <td class="cell-id">#<?= $r['id'] ?></td>
              <td class="cell-name"><?= htmlspecialchars($r['ad_soyad']) ?></td>
              <td class="cell-email"><?= htmlspecialchars($r['email']) ?></td>
              <td><a class="map-link" href="https://maps.google.com/?q=<?= $r['lat'] ?>,<?= $r['lng'] ?>" target="_blank"><i class="fas fa-map-pin"></i><?= round($r['lat'],4) ?>, <?= round($r['lng'],4) ?></a></td>
              <td class="cell-date"><?= date('d.m.Y H:i',strtotime($r['created_at'])) ?></td>
              <td><a href="admin.php?action=reports&delete_report=<?= $r['id'] ?>" class="btn-delete" onclick="return confirm('İhbarı silmek istiyor musun?')"><i class="fas fa-trash-alt"></i> Sil</a></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </div>

      <!-- ══ MEDYA ══ -->
      <?php elseif ($action == 'media'):
        $photos=$conn->query("SELECT p.id,p.file_path,p.created_at,u.ad_soyad,u.email FROM photos p JOIN users u ON p.user_id=u.id ORDER BY p.created_at DESC");
        $audios=$conn->query("SELECT a.id,a.file_path,a.created_at,u.ad_soyad,u.email FROM audios a JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC");
        $photo_rows=[]; while($p=$photos->fetch_assoc()) $photo_rows[]=$p;
        $audio_rows=[]; while($a=$audios->fetch_assoc()) $audio_rows[]=$a;
      ?>
        <div class="section-header"><h1>Medya Yönetimi</h1><p><?= count($photo_rows) ?> fotoğraf · <?= count($audio_rows) ?> ses kaydı</p></div>
        <div class="card">
          <div class="media-tabs">
            <button class="tab-btn active" onclick="switchTab('photos',this)"><i class="fas fa-images" style="margin-right:6px;"></i>Fotoğraflar (<?= count($photo_rows) ?>)</button>
            <button class="tab-btn" onclick="switchTab('audios',this)"><i class="fas fa-headphones" style="margin-right:6px;"></i>Ses Kayıtları (<?= count($audio_rows) ?>)</button>
          </div>
          <div class="tab-panel active" id="tab-photos">
            <?php if(count($photo_rows)==0): ?><div class="empty-state"><i class="fas fa-image"></i><p>Henüz fotoğraf yüklenmemiş.</p></div>
            <?php else: ?><div class="photo-grid"><?php foreach($photo_rows as $photo): ?>
              <div class="photo-item">
                <img src="<?= htmlspecialchars($photo['file_path']) ?>" alt="Fotoğraf" loading="lazy" onerror="this.style.display='none'">
                <div class="photo-meta">
                  <div class="pname"><?= htmlspecialchars($photo['ad_soyad']) ?></div>
                  <div class="pdate"><?= date('d.m.Y H:i',strtotime($photo['created_at'])) ?></div>
                  <a href="admin.php?action=media&delete_photo=<?= $photo['id'] ?>" class="btn-delete" onclick="return confirm('Fotoğrafı silmek istiyor musun?')" style="width:100%;justify-content:center;"><i class="fas fa-trash-alt"></i> Sil</a>
                </div>
              </div>
            <?php endforeach; ?></div><?php endif; ?>
          </div>
          <div class="tab-panel" id="tab-audios">
            <?php if(count($audio_rows)==0): ?><div class="empty-state"><i class="fas fa-microphone-slash"></i><p>Henüz ses kaydı yüklenmemiş.</p></div>
            <?php else: ?><div class="audio-list-wrap"><?php foreach($audio_rows as $audio): ?>
              <div class="audio-row">
                <div class="audio-icon"><i class="fas fa-waveform-lines"></i></div>
                <div class="audio-meta"><div class="aname"><?= htmlspecialchars($audio['ad_soyad']) ?></div><div class="adate"><?= date('d.m.Y H:i',strtotime($audio['created_at'])) ?></div></div>
                <div class="audio-player"><audio controls><source src="<?= htmlspecialchars($audio['file_path']) ?>" type="audio/webm">Tarayıcınız desteklemiyor.</audio></div>
                <a href="admin.php?action=media&delete_audio=<?= $audio['id'] ?>" class="btn-delete" onclick="return confirm('Ses kaydını silmek istiyor musun?')"><i class="fas fa-trash-alt"></i></a>
              </div>
            <?php endforeach; ?></div><?php endif; ?>
          </div>
        </div>

      <!-- ══ BULUŞMA TAKİBİ ══ -->
      <?php elseif ($action == 'bulusmalar'):
        $result = $conn->query("
          SELECT b.id, b.kisi_adi, b.yakinlik, b.adres, b.bulusma_tarihi, b.bulusma_saati,
                 b.suphe_nedeni, b.created_at,
                 u.id as user_id, u.ad_soyad, u.email
          FROM bulusmalar b
          JOIN users u ON b.user_id = u.id
          ORDER BY b.created_at DESC
        ");
        $bulusmalar = [];
        if ($result) while($row=$result->fetch_assoc()) $bulusmalar[]=$row;

        // Kullanıcı bazlı özet
        $ozet_result = $conn->query("
          SELECT u.id, u.ad_soyad, u.email,
                 COUNT(b.id) as toplam,
                 MAX(b.created_at) as son_kayit
          FROM users u
          JOIN bulusmalar b ON b.user_id = u.id
          GROUP BY u.id
          ORDER BY toplam DESC
        ");
        $ozetler = [];
        if ($ozet_result) while($row=$ozet_result->fetch_assoc()) $ozetler[]=$row;

        $yakinlik_colors = ['Arkadaş'=>'#60a5fa','Aile'=>'#34d399','İş Arkadaşı'=>'#fb923c','Tanıdık'=>'#a78bfa','Yabancı'=>'#f87171','Diğer'=>'#6b7280'];
      ?>

        <div class="section-header">
          <h1>Buluşma Takibi</h1>
          <p>Kullanıcıların kaydettiği şüpheli buluşma kayıtları. Toplam <?= count($bulusmalar) ?> kayıt.</p>
        </div>

        <!-- Özet istatistikler -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
          <div class="stat-card" style="--stat-color:#fb923c;">
            <div class="stat-row"><div class="stat-icon-wrap si-bulusma"><i class="fas fa-calendar-alt"></i></div></div>
            <div class="stat-num"><?= count($bulusmalar) ?></div>
            <div class="stat-label">Toplam Buluşma</div>
          </div>
          <?php
          $yabanci_count = count(array_filter($bulusmalar, fn($b) => $b['yakinlik'] === 'Yabancı'));
          $suphe_count   = count(array_filter($bulusmalar, fn($b) => !empty($b['suphe_nedeni'])));
          ?>
          <div class="stat-card" style="--stat-color:#f87171;">
            <div class="stat-row"><div class="stat-icon-wrap" style="background:rgba(248,113,113,0.15);color:#f87171;"><i class="fas fa-user-secret"></i></div></div>
            <div class="stat-num"><?= $yabanci_count ?></div>
            <div class="stat-label">Yabancı Kişi Buluşması</div>
          </div>
          <div class="stat-card" style="--stat-color:#f59e0b;">
            <div class="stat-row"><div class="stat-icon-wrap" style="background:rgba(245,158,11,0.15);color:#f59e0b;"><i class="fas fa-exclamation-triangle"></i></div></div>
            <div class="stat-num"><?= $suphe_count ?></div>
            <div class="stat-label">Şüphe Notu Olan</div>
          </div>
        </div>

        <!-- Kullanıcı Bazlı Özet -->
        <?php if (count($ozetler) > 0): ?>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-head">
            <div class="card-title"><i class="fas fa-users"></i> Kullanıcı Bazlı Özet</div>
            <span class="card-count"><?= count($ozetler) ?> kullanıcı</span>
          </div>
          <div class="table-wrap"><table class="data-table">
            <thead><tr><th>Kullanıcı</th><th>E-posta</th><th>Toplam Kayıt</th><th>Son Kayıt</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php foreach($ozetler as $oz): ?>
              <tr>
                <td class="cell-name"><?= htmlspecialchars($oz['ad_soyad']) ?></td>
                <td class="cell-email"><?= htmlspecialchars($oz['email']) ?></td>
                <td><span style="background:rgba(251,146,60,0.12);color:#fb923c;padding:4px 12px;border-radius:20px;font-size:14px;font-weight:700;font-family:'Syne',sans-serif;"><?= (int)$oz['toplam'] ?></span></td>
                <td class="cell-date"><?= $oz['son_kayit'] ? date('d.m.Y H:i',strtotime($oz['son_kayit'])) : '—' ?></td>
                <td>
                  <a href="admin.php?action=bulusmalar&filter_user=<?= $oz['id'] ?>" class="btn-view"><i class="fas fa-eye"></i> Kayıtları Gör</a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table></div>
        </div>
        <?php endif; ?>

        <!-- Tüm Buluşmalar Tablosu -->
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="fas fa-list-ul"></i>
              <?php if(isset($_GET['filter_user'])): ?>
                Kullanıcı Buluşmaları
                <a href="admin.php?action=bulusmalar" style="font-size:11px;color:var(--muted);margin-left:8px;text-decoration:none;background:var(--surface-3);padding:2px 8px;border-radius:6px;">← Tümünü Gör</a>
              <?php else: ?>
                Tüm Buluşma Kayıtları
              <?php endif; ?>
            </div>
            <span class="card-count"><?= count($bulusmalar) ?> kayıt</span>
          </div>
          <div class="table-wrap">
            <?php if(count($bulusmalar)==0): ?>
              <div class="empty-state"><i class="fas fa-calendar-times"></i><p>Buluşma kaydı bulunmuyor.</p></div>
            <?php else: ?>
            <table class="data-table">
              <thead><tr><th>#</th><th>Kullanıcı</th><th>Buluşulan Kişi</th><th>Yakınlık</th><th>Adres</th><th>Tarih & Saat</th><th>Şüphe Notu</th><th>İşlem</th></tr></thead>
              <tbody>
              <?php foreach($bulusmalar as $b):
                // filter_user uygulanıyorsa filtrele
                if(isset($_GET['filter_user']) && is_numeric($_GET['filter_user']) && $b['user_id'] != intval($_GET['filter_user'])) continue;
                $renk = $yakinlik_colors[$b['yakinlik']] ?? '#6b7280';
                $tarih_goster = $b['bulusma_tarihi'] ? date('d.m.Y',strtotime($b['bulusma_tarihi'])) : '—';
                $saat_goster  = $b['bulusma_saati']  ? substr($b['bulusma_saati'],0,5) : '—';
              ?>
                <tr>
                  <td class="cell-id">#<?= $b['id'] ?></td>
                  <td>
                    <span class="cell-name"><?= htmlspecialchars($b['ad_soyad']) ?></span>
                    <span class="cell-sub"><?= htmlspecialchars($b['email']) ?></span>
                  </td>
                  <td class="cell-name"><?= htmlspecialchars($b['kisi_adi']) ?></td>
                  <td>
                    <?php if($b['yakinlik']): ?>
                      <span class="bulusma-badge" style="background:<?= $renk ?>22;color:<?= $renk ?>;"><?= htmlspecialchars($b['yakinlik']) ?></span>
                    <?php else: ?><span style="color:var(--muted);font-size:12px;">—</span><?php endif; ?>
                  </td>
                  <td style="font-size:13px;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($b['adres']) ?>"><?= htmlspecialchars($b['adres']) ?></td>
                  <td class="cell-date"><?= $tarih_goster ?><br><span style="color:var(--brand);font-weight:600;"><?= $saat_goster ?></span></td>
                  <td>
                    <?php if(!empty($b['suphe_nedeni'])): ?>
                      <button class="btn-view" onclick="bulusmaDetayAc(<?= htmlspecialchars(json_encode($b),ENT_QUOTES) ?>)" style="font-size:11px;padding:5px 10px;">
                        <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i> Notu Gör
                      </button>
                    <?php else: ?><span style="color:var(--muted);font-size:12px;">—</span><?php endif; ?>
                  </td>
                  <td>
                    <div style="display:flex;gap:6px;">
                      <button class="btn-view" onclick="bulusmaDetayAc(<?= htmlspecialchars(json_encode($b),ENT_QUOTES) ?>)" style="padding:6px 10px;"><i class="fas fa-eye"></i></button>
                      <a href="admin.php?action=bulusmalar&delete_bulusma=<?= $b['id'] ?>" class="btn-delete" onclick="return confirm('Bu buluşma kaydını silmek istiyor musun?')" style="padding:6px 10px;"><i class="fas fa-trash-alt"></i></a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

        <!-- Buluşma Detay Modal -->
        <div class="bulusma-detail-modal" id="bulusmaModal">
          <div class="bulusma-detail-box">
            <div class="bulusma-detail-head">
              <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-calendar-check" style="color:var(--brand);"></i>
                Buluşma Detayı
              </div>
              <button class="close-modal-btn" onclick="bulusmaModalKapat()">✕</button>
            </div>
            <div class="bulusma-detail-body" id="bulusmaModalIcerik"></div>
          </div>
        </div>

        <script>
        function bulusmaDetayAc(b) {
          const yakinlikRenk = {'Arkadaş':'#60a5fa','Aile':'#34d399','İş Arkadaşı':'#fb923c','Tanıdık':'#a78bfa','Yabancı':'#f87171','Diğer':'#6b7280'};
          const renk = yakinlikRenk[b.yakinlik] || '#6b7280';
          const tarih = b.bulusma_tarihi ? b.bulusma_tarihi.split('-').reverse().join('.') : '—';
          const saat  = b.bulusma_saati  ? b.bulusma_saati.substring(0,5) : '—';
          let html = '';

          // Başlık alanı
          html += `<div style="display:flex;align-items:center;gap:14px;padding-bottom:14px;border-bottom:1px solid var(--border);">
            <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand-2));display:flex;align-items:center;justify-content:center;color:white;font-size:20px;font-weight:800;font-family:'Syne',sans-serif;flex-shrink:0;">
              ${(b.kisi_adi||'?')[0].toUpperCase()}
            </div>
            <div>
              <div style="font-size:17px;font-weight:700;color:var(--text);font-family:'Syne',sans-serif;">${escHtml(b.kisi_adi)}</div>
              ${b.yakinlik ? `<span class="bulusma-badge" style="background:${renk}22;color:${renk};margin-top:4px;">${escHtml(b.yakinlik)}</span>` : ''}
            </div>
          </div>`;

          html += detailRow('fas fa-user','#818cf8','Kullanıcı', b.ad_soyad + ' (' + b.email + ')');
          html += detailRow('fas fa-map-marker-alt','var(--brand)','Adres', b.adres || '—');
          html += detailRow('fas fa-calendar-alt','#a78bfa','Tarih & Saat', tarih + ' — ' + saat);

          if (b.suphe_nedeni) {
            html += `<div class="suphe-box">
              <div class="suphe-label"><i class="fas fa-exclamation-triangle"></i> Şüphe Nedeni</div>
              <div class="suphe-text">${escHtml(b.suphe_nedeni)}</div>
            </div>`;
          }

          html += detailRow('fas fa-clock','var(--muted)','Kayıt Tarihi', b.created_at ? b.created_at.substring(0,16) : '—');

          document.getElementById('bulusmaModalIcerik').innerHTML = html;
          document.getElementById('bulusmaModal').classList.add('show');
        }

        function bulusmaModalKapat() {
          document.getElementById('bulusmaModal').classList.remove('show');
        }

        function detailRow(icon, color, label, value) {
          return `<div class="detail-row">
            <i class="${icon}" style="color:${color};"></i>
            <div class="detail-row-content">
              <div class="dlabel">${label}</div>
              <div class="dval">${escHtml(String(value||'—'))}</div>
            </div>
          </div>`;
        }

        function escHtml(s) {
          return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        document.getElementById('bulusmaModal').addEventListener('click', function(e) {
          if(e.target === this) bulusmaModalKapat();
        });
        </script>

      <!-- ══ POLİS & ACİL ÇAĞRILAR ══ -->
      <?php elseif ($action == 'calls'):
        $call_summary = $conn->query("SELECT u.id,u.ad_soyad,u.email,u.kan_grubu,COUNT(s.id) AS toplam,SUM(s.type='polis' OR s.type='police') AS polis,SUM(s.type='acil_durum' OR s.type='emergency' OR s.type='sos') AS acil,MAX(s.created_at) AS son_cagri FROM users u JOIN sent_logs s ON s.user_id=u.id WHERE u.is_admin=0 GROUP BY u.id ORDER BY toplam DESC");
        $summary_rows=[]; if($call_summary) while($row=$call_summary->fetch_assoc()) $summary_rows[]=$row;
        $all_logs=$conn->query("SELECT s.id,s.type,s.created_at,u.ad_soyad,u.email FROM sent_logs s JOIN users u ON s.user_id=u.id ORDER BY s.created_at DESC LIMIT 100");
        $log_rows=[]; if($all_logs) while($row=$all_logs->fetch_assoc()) $log_rows[]=$row;
        $bugun_r=$conn->query("SELECT COUNT(*) as c FROM sent_logs WHERE DATE(created_at)=CURDATE()");
        $bugun_calls=$bugun_r?$bugun_r->fetch_assoc()['c']:0;
        $hafta_r=$conn->query("SELECT COUNT(*) as c FROM sent_logs WHERE created_at>=DATE_SUB(NOW(),INTERVAL 7 DAY)");
        $hafta_calls=$hafta_r?$hafta_r->fetch_assoc()['c']:0;
      ?>
        <div class="section-header"><h1>Polis &amp; Acil Çağrı Geçmişi</h1><p>Kullanıcıların acil çağrı kayıtları.</p></div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
          <div class="stat-card" style="--stat-color:#f87171;"><div class="stat-row"><div class="stat-icon-wrap" style="background:rgba(248,113,113,0.15);color:#f87171;"><i class="fas fa-phone-volume"></i></div></div><div class="stat-num"><?= $toplam_calls ?></div><div class="stat-label">Toplam Çağrı</div></div>
          <div class="stat-card" style="--stat-color:#f59e0b;"><div class="stat-row"><div class="stat-icon-wrap" style="background:rgba(245,158,11,0.15);color:#f59e0b;"><i class="fas fa-calendar-day"></i></div></div><div class="stat-num"><?= $bugun_calls ?></div><div class="stat-label">Bugünkü Çağrılar</div></div>
          <div class="stat-card" style="--stat-color:#60a5fa;"><div class="stat-row"><div class="stat-icon-wrap" style="background:rgba(96,165,250,0.15);color:#60a5fa;"><i class="fas fa-calendar-week"></i></div></div><div class="stat-num"><?= $hafta_calls ?></div><div class="stat-label">Son 7 Günde</div></div>
        </div>
        <div class="card" style="margin-bottom:20px;">
          <div class="card-head"><div class="card-title"><i class="fas fa-users"></i> Kullanıcı Bazlı Özet</div><span class="card-count"><?= count($summary_rows) ?> kullanıcı</span></div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>Kullanıcı</th><th>E-posta</th><th>Kan Grubu</th><th>Toplam</th><th>Polis</th><th>Acil/SOS</th><th>Son Çağrı</th><th>İşlem</th></tr></thead><tbody>
          <?php if(count($summary_rows)==0): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-phone-slash"></i><p>Henüz çağrı kaydı yok.</p></div></td></tr>
          <?php else: foreach($summary_rows as $row): ?>
            <tr>
              <td class="cell-name"><?= htmlspecialchars($row['ad_soyad']) ?></td>
              <td class="cell-email"><?= htmlspecialchars($row['email']) ?></td>
              <td><?php if($row['kan_grubu']): ?><span style="background:rgba(248,113,113,0.15);color:#f87171;padding:3px 10px;border-radius:20px;font-size:13px;font-weight:700;"><?= htmlspecialchars($row['kan_grubu']) ?></span><?php else: ?><span style="color:var(--muted);font-size:12px;">—</span><?php endif; ?></td>
              <td><span style="background:rgba(239,68,68,0.12);color:#f87171;padding:4px 12px;border-radius:20px;font-size:14px;font-weight:700;font-family:'Syne',sans-serif;"><?= (int)$row['toplam'] ?></span></td>
              <td><?php $p=(int)$row['polis']; ?><span style="background:<?= $p>0?'rgba(59,130,246,0.12)':'var(--surface-3)' ?>;color:<?= $p>0?'#60a5fa':'var(--muted)' ?>;padding:3px 10px;border-radius:20px;font-size:13px;font-weight:600;"><?= $p ?></span></td>
              <td><?php $a=(int)$row['acil']; ?><span style="background:<?= $a>0?'rgba(245,158,11,0.12)':'var(--surface-3)' ?>;color:<?= $a>0?'#f59e0b':'var(--muted)' ?>;padding:3px 10px;border-radius:20px;font-size:13px;font-weight:600;"><?= $a ?></span></td>
              <td class="cell-date"><?= $row['son_cagri']?date('d.m.Y H:i',strtotime($row['son_cagri'])):'—' ?></td>
              <td><a href="admin.php?action=calls&delete_user_calls=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Tüm çağrı kayıtlarını silmek istiyor musun?')"><i class="fas fa-trash-alt"></i> Tüm Kayıtları Sil</a></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </div>
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="fas fa-list-ul"></i> Son 100 Çağrı Kaydı</div>
            <div style="display:flex;align-items:center;gap:10px;">
              <span class="card-count"><?= count($log_rows) ?> kayıt</span>
              <?php if(count($log_rows)>0): ?>
              <a href="admin.php?action=calls&delete_all_calls=1" class="btn-delete" style="font-size:12px;" onclick="return confirm('Tüm çağrı kayıtlarını silmek istiyor musun?')"><i class="fas fa-trash-alt"></i> Tümünü Sil</a>
              <?php endif; ?>
            </div>
          </div>
          <div class="table-wrap"><table class="data-table"><thead><tr><th>#</th><th>Kullanıcı</th><th>E-posta</th><th>Çağrı Türü</th><th>Tarih & Saat</th><th>İşlem</th></tr></thead><tbody>
          <?php if(count($log_rows)==0): ?><tr><td colspan="5"><div class="empty-state"><i class="fas fa-phone-slash"></i><p>Kayıt yok.</p></div></td></tr>
          <?php else: foreach($log_rows as $lg): $t=strtolower($lg['type']??'');
            if($t==='polis'||$t==='police'){ $bg='rgba(59,130,246,0.12)'; $c='#60a5fa'; $ico='fas fa-shield-halved'; $lbl='Polis'; }
            else { $bg='rgba(239,68,68,0.12)'; $c='#f87171'; $ico='fas fa-triangle-exclamation'; $lbl='Acil / SOS'; }
          ?>
            <tr>
              <td class="cell-id">#<?= $lg['id'] ?></td>
              <td class="cell-name"><?= htmlspecialchars($lg['ad_soyad']) ?></td>
              <td class="cell-email"><?= htmlspecialchars($lg['email']) ?></td>
              <td><span style="background:<?= $bg ?>;color:<?= $c ?>;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:5px;"><i class="<?= $ico ?>"></i> <?= $lbl ?></span></td>
              <td class="cell-date"><?= date('d.m.Y H:i',strtotime($lg['created_at'])) ?></td>
              <td><a href="admin.php?action=calls&delete_call=<?= $lg['id'] ?>" class="btn-delete" onclick="return confirm('Bu kaydı silmek istiyor musun?')"><i class="fas fa-trash-alt"></i> Sil</a></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody></table></div>
        </div>

      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function tick(){
  const now=new Date();
  const el=document.getElementById('clock');
  if(el) el.textContent=now.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
tick(); setInterval(tick,1000);

function switchTab(tab,btn){
  document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.getElementById('tab-'+tab).classList.add('active');
  btn.classList.add('active');
}
</script>
</body>
</html>
