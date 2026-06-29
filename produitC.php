<?php
session_start();
require "connexion.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
/* Produits */
$stmt = $pdo->query("SELECT * FROM Produit");
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Produits</title>
<style>
body{
    font-family:Segoe UI;
    background:#fff0f6;
    margin:0;
}
.container{
    width:90%;
    max-width:1000px;
    margin:30px auto;
}
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.btn{
    display:inline-block;
    padding:10px;
    background:#d63384;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
</style>
</head>
<body>
<div class="container">
<h2>🛍️ Liste des Produits</h2>
<?php foreach($produits as $p): ?>
<div class="card">
    <h3><?= htmlspecialchars($p['nom']) ?></h3>
    <p>Prix : <?= $p['prix'] ?> FCFA</p>
    <p>Stock : <?= $p['quantite'] ?></p>
</div>
<?php endforeach; ?>

<a class="btn" href="client_dashboard.php">⬅ Retour</a>

</div>
</body>
</html>