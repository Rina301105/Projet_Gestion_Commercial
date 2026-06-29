<?php
session_start();
require "connexion.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$idClient = $_SESSION['user']['idClient'];
$stmt = $pdo->prepare("
    SELECT * FROM Commande
    WHERE idClient = ?
    ORDER BY idCommande DESC
");
$stmt->execute([$idClient]);
$commandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes Commandes</title>
<style>
body{
    font-family:Segoe UI;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
    margin:0;
}
.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}
h2{
    text-align:center;
    color:#d63384;
}
table{
    width:100%;
    background:white;
    border-collapse:collapse;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
th{
    background:#d63384;
    color:white;
    padding:12px;
}
td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}
.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    font-weight:bold;
}
</style>
</head>
<body>
<div class="container">
<h2>📦 Mes Commandes</h2>
<table>
<tr>
<th>ID</th>
<th>Date</th>
<th>Montant</th>
<th>Statut</th>
</tr>
<?php foreach($commandes as $c): ?>
<tr>
<td><?= $c['idCommande'] ?></td>
<td><?= $c['dateCommande'] ?></td>
<td><?= $c['montant'] ?> FCFA</td>
<td><?= $c['statut'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<a class="back" href="client_dashboard.php">⬅ Retour</a>

</div>
</body>
</html>