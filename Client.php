<?php
require "connexion.php";

$msg = "";

if (isset($_POST['inscription'])) {

    $stmt = $pdo->prepare("
        INSERT INTO Client(nom, prenom, telephone, adresse, motDePasse)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['adresse'],
        password_hash($_POST['motDePasse'], PASSWORD_BCRYPT)
    ]);

    $msg = "✔ Client inscrit avec succès";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription Client</title>

<style>
body{
    font-family:"Segoe UI";
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    border-radius:20px;
    width:350px;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
}

h2{ color:#d63384; text-align:center; }

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:12px;
    border:1px solid #ffd6e7;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(90deg,#ff69b4,#d63384);
    color:white;
    font-weight:bold;
}
.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    font-weight:bold;
}

.msg{
    text-align:center;
    color:green;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="box">

<h2>Inscription Client</h2>

<div class="msg"><?= $msg ?></div>

<form method="POST">

<input name="nom" placeholder="Nom" required>
<input name="prenom" placeholder="Prénom" required>
<input name="telephone" placeholder="Téléphone" required>
<input name="adresse" placeholder="Adresse" required>
<input type="password" name="motDePasse" placeholder="Mot de passe" required>

<button name="inscription">Créer compte</button>

</form>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>