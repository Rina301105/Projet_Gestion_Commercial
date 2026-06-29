<?php
session_start();
require "connexion.php";



/* ========================
   AJOUT FACTURE
======================== */
if(isset($_POST['ajouter'])){

    $stmt = $pdo->prepare("
        INSERT INTO Facture(dateFacture, montant, idCommande)
        VALUES(?,?,?)
    ");

    $stmt->execute([
        $_POST['dateFacture'],
        $_POST['montant'],
        $_POST['idCommande']
    ]);
}

/* ========================
   SUPPRESSION
======================== */
if(isset($_GET['delete'])){

    $stmt = $pdo->prepare("DELETE FROM Facture WHERE idFacture=?");
    $stmt->execute([$_GET['delete']]);
}

/* ========================
   EDIT
======================== */
$edit = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare("SELECT * FROM Facture WHERE idFacture=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* ========================
   UPDATE
======================== */
if(isset($_POST['modifier'])){

    $stmt = $pdo->prepare("
        UPDATE Facture
        SET dateFacture=?, montant=?, idCommande=?
        WHERE idFacture=?
    ");

    $stmt->execute([
        $_POST['dateFacture'],
        $_POST['montant'],
        $_POST['idCommande'],
        $_POST['id']
    ]);
}

/* ========================
   LISTE FACTURES
======================== */
$factures = $pdo->query("
    SELECT * FROM Facture
    ORDER BY idFacture DESC
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Factures</title>

<style>

body{
    margin:0;
    font-family:"Segoe UI";
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
    font-size:35px;
}

/* FORM */
.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:12px;
    border:1px solid #ffd6e7;
}

input:focus{
    outline:none;
    border-color:#d63384;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(90deg,#ff69b4,#d63384);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
}

th{
    background:#ffe4ec;
    color:#d63384;
    padding:12px;
}

td{
    text-align:center;
    padding:12px;
    border-bottom:1px solid #ffe4ec;
}

tr:hover{
    background:#fff7fa;
}

/* ACTIONS */
a{
    text-decoration:none;
    padding:6px 10px;
    border-radius:8px;
    color:white;
}
.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    font-weight:bold;
}

.edit{ background:#0d6efd; }
.del{ background:#dc3545; }

.montant{
    color:#d63384;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h2>🧾 Gestion des Factures</h2>

<!-- FORM -->
<div class="card">

<form method="POST">

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit['idFacture'] ?>">
<?php endif; ?>

<input type="date"
name="dateFacture"
value="<?= $edit['dateFacture'] ?? '' ?>"
required>

<input type="number"
step="0.01"
name="montant"
placeholder="Montant"
value="<?= $edit['montant'] ?? '' ?>"
required>

<input type="number"
name="idCommande"
placeholder="ID Commande"
value="<?= $edit['idCommande'] ?? '' ?>"
required>

<button type="submit"
name="<?= $edit ? 'modifier' : 'ajouter' ?>">

<?= $edit ? "Modifier" : "Ajouter" ?>

</button>

</form>

</div>

<!-- TABLE -->
<table>

<tr>
<th>ID</th>
<th>Date</th>
<th>Montant</th>
<th>Commande</th>
<th>Actions</th>
</tr>

<?php foreach($factures as $f): ?>

<tr>

<td><?= $f['idFacture'] ?></td>

<td><?= $f['dateFacture'] ?></td>

<td class="montant">
<?= $f['montant'] ?> FCFA
</td>

<td><?= $f['idCommande'] ?></td>

<td>

<a class="edit"
href="?edit=<?= $f['idFacture'] ?>">Edit</a>

<a class="del"
href="?delete=<?= $f['idFacture'] ?>"
onclick="return confirm('Supprimer ?')">Del</a>

</td>

</tr>

<?php endforeach; ?>

</table>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>