<?php
session_start();
require "connexion.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$idClient = $_SESSION['user']['idClient'];
$stmt = $pdo->prepare("
    SELECT f.*
    FROM Facture f
    JOIN Commande c ON f.idCommande = c.idCommande
    WHERE c.idClient = ?
");
$stmt->execute([$idClient]);
$factures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes Factures</title>
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
<h2>🧾 Mes Factures</h2>
<table>
<tr>
<th>ID</th>
<th>Date</th>
<th>Montant</th>
</tr>
<?php foreach($factures as $f): ?>
<tr>
<td><?= $f['idFacture'] ?></td>
<td><?= $f['dateFacture'] ?></td>
<td><?= $f['montant'] ?> FCFA</td>
</tr>
<?php endforeach; ?>
</table>

<a class="back" href="client_dashboard.php">⬅ Retour</a>

</div>
</body>
</html>