<?php
session_start();
require "connexion.php";

/* ================= SECURITE ================= */
if (!isset($_SESSION['user']['idClient'])) {
    header("Location: index.php");
    exit();
}

$idClient = $_SESSION['user']['idClient'];

/* ================= PAIEMENTS ================= */
$stmt = $pdo->prepare("
    SELECT 
        p.idPaiement,
        p.datePaiement,
        p.montant,
        p.typePaiement
    FROM Paiement p
    INNER JOIN Facture f ON p.idFacture = f.idFacture
    INNER JOIN Commande c ON f.idCommande = c.idCommande
    WHERE c.idClient = ?
    ORDER BY p.idPaiement DESC
");

$stmt->execute([$idClient]);
$paiements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes Paiements</title>

<style>

body{
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
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

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
}

th{
    background:#d63384;
    color:white;
    padding:12px;
}

td{
    text-align:center;
    padding:12px;
    border-bottom:1px solid #f3f3f3;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:20px;
    color:#666;
}

/* BACK */
.back-btn{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    font-weight:bold;
}

.back-btn:hover{
    opacity:0.7;
}

</style>
</head>

<body>

<div class="container">

<h2>💳 Mes Paiements</h2>

<table>

<tr>
<th>ID</th>
<th>Date</th>
<th>Montant</th>
<th>Type</th>
</tr>

<?php if (!empty($paiements)): ?>

    <?php foreach($paiements as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['idPaiement']) ?></td>
        <td><?= htmlspecialchars($p['datePaiement']) ?></td>
        <td><?= htmlspecialchars($p['montant']) ?> FCFA</td>
        <td><?= htmlspecialchars($p['typePaiement']) ?></td>
    </tr>
    <?php endforeach; ?>

<?php else: ?>

<tr>
    <td colspan="4" class="empty">Aucun paiement trouvé</td>
</tr>

<?php endif; ?>

</table>

<a class="back-btn" href="client_dashboard.php">⬅ Retour</a>

</div>

</body>
</html>