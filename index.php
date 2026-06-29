<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    background:linear-gradient(135deg,#ffe4ec,#fff0f6);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.box{
    width:360px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
h2{
    text-align:center;
    color:#d63384;
}
input{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #ddd;
}
button{
    width:100%;
    margin-top:15px;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#d63384;
    color:white;
    font-weight:bold;
    cursor:pointer;
}
.error{
    color:red;
    text-align:center;
    margin-bottom:10px;
}
</style>
</head>
<body>
<div class="box">
<h2>Connexion</h2>
<?php
if(isset($_SESSION['error'])){
    echo "<div class='error'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}
?>
<form method="POST" action="traitement.php">
<input type="text" name="login" placeholder="Login ou téléphone" required>
<input type="password" name="password" placeholder="Mot de passe" required>
<button type="submit">Connexion</button>
</form>
</div>
</body>
</html>