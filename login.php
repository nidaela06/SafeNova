<?php
ob_start();
ini_set("session.save_path", "/tmp");
session_start();

// HTTPS zorla (Railway)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    exit();
}

// Güvenlik başlıkları
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff'); if(isset($_SESSION['user_id'])){ header("Location: index.php"); exit(); } ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SafeNova Giriş</title>

<style>

body{
margin:0;
font-family:Arial;
background:linear-gradient(135deg,#ff4fa0,#8a2be2);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.card{
background:white;
padding:40px;
border-radius:20px;
width:260px;
text-align:center;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.logo{
width:120px;
margin-bottom:10px;
}

input{
width:100%;
padding:10px;
margin:8px 0;
border-radius:10px;
border:1px solid #ccc;
}

button{
width:100%;
padding:10px;
border:none;
border-radius:20px;
background:#ff2e7a;
color:white;
font-size:16px;
}

a{
color:#ff2e7a;
text-decoration:none;
}

</style>

</head>

<body>

<form action="giris_kontrol.php" method="POST">

<div class="card">

<img src="logo.png" class="logo">

<h2>Giriş Yap</h2>

<input type="email" name="email" placeholder="E-posta">
<input type="password" name="sifre" placeholder="Şifre">

<?php
$hata_mesaj = '';
if(isset($_GET['hata'])){
    if($_GET['hata']==='yanlis') $hata_mesaj = 'E-posta veya şifre yanlış!';
    elseif($_GET['hata']==='bos') $hata_mesaj = 'E-posta ve şifre boş bırakılamaz!';
}
if($hata_mesaj): ?>
<p style="color:#ff2e7a;font-size:13px;margin:6px 0;"><?= htmlspecialchars($hata_mesaj) ?></p>
<?php endif; ?>
<button type="submit">Giriş Yap</button>


<p>Hesabın yok mu?</p>

<a href="register.php">Kayıt Ol</a>

</div>

</form>
</body>
</html>
