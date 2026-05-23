<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SafeNova Kayıt</title>

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
    width:220px;
    margin-bottom:10px;
}

input{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:10px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:20px;
    background:#ff2e7a;
    color:white;
    font-size:16px;
    cursor:pointer;
}

a{
    color:#ff2e7a;
    text-decoration:none;
}
</style>

</head>
<body>

<form action="kayit_kontrol.php" method="POST">
    <div class="card">

        <img src="logo.png" class="logo">

        <h2>Kayıt Ol</h2>

        <input type="text" name="adsoyad" placeholder="Ad Soyad" required>
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <input type="password" name="sifre_tekrar" placeholder="Şifre Tekrar" required>

        <button type="submit">Kayıt Ol</button>

        <p>Zaten hesabın var mı?</p>
        <a href="login.php">Giriş Yap</a>

    </div>
</form>

</body>
</html>