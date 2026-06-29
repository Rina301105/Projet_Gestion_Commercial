<?php
session_start();
/* Sécurité */
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$client = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Client</title>
<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
}
/* HEADER */
.header{
    background:white;
    padding:25px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}
.header h2{
    color:#d63384;
}
/* MENU */
.menu{
    width:90%;
    max-width:900px;
    margin:40px auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}
.menu a{
    display:block;
    text-decoration:none;
    background:white;
    padding:25px;
    border-radius:15px;
    text-align:center;
    color:#d63384;
    font-weight:bold;
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
    transition:0.3s;
}
.menu a:hover{
    transform:translateY(-5px);
}
/* INFO */
.info{
    text-align:center;
    margin-top:20px;
    color:#555;
}
.logout{
    margin-top:10px;
    display:inline-block;
    color:white;
    background:#d63384;
    padding:10px 20px;
    border-radius:10px;
    text-decoration:none;
}
</style>
</head>
<body>
<div class="header">
    <h2>💖 Bonjour <?= htmlspecialchars($client['prenom']) ?></h2>
    <p>Tableau de bord client</p>
</div>
<div class="menu">
<a href="produitC.php">🛍️ Voir les produits</a>
<a href="commandeC.php">📦 Mes commandes</a>
<a href="paimentC.php">💳 Mes paiements</a>
<a href="factureC.php">🧾 Mes factures</a>
</div>
<div class="info">
<p>Gérez vos activités depuis votre espace personnel</p>
<a class="logout" href="logout.php">Déconnexion</a>
</div>
</body>
</html>