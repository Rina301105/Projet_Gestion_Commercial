<?php
require "connexion.php";

/* ================= MESSAGE ================= */
$message = "";

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Commande WHERE idCommande=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: Commande.php");
    exit;
}

/* ================= EDIT LOAD ================= */
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM Commande WHERE idCommande=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* ================= INSERT ================= */
if (isset($_POST['ajouter'])) {

    $stmt = $pdo->prepare("
        INSERT INTO Commande(dateCommande, montant, statut, idClient)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $_POST['dateCommande'],
        $_POST['montant'],
        $_POST['statut'],
        $_POST['idClient']
    ]);

    header("Location: Commande.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['modifier'])) {

    $stmt = $pdo->prepare("
        UPDATE Commande
        SET dateCommande=?, montant=?, statut=?, idClient=?
        WHERE idCommande=?
    ");

    $stmt->execute([
        $_POST['dateCommande'],
        $_POST['montant'],
        $_POST['statut'],
        $_POST['idClient'],
        $_POST['id']
    ]);

    header("Location: Commande.php");
    exit;
}

/* ================= LIST ================= */
$commandes = $pdo->query("
    SELECT c.*, cl.nom, cl.prenom
    FROM Commande c
    JOIN Client cl ON c.idClient = cl.idClient
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commandes</title>

<style>
body{
    font-family:"Segoe UI", Arial;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
    margin:0;
    padding:0;
}

.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}

h2{
    text-align:center;
    color:#d63384;
    font-size:34px;
    margin-bottom:20px;
}

/* FORM */
.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
    margin-bottom:25px;
}

input, select{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:12px;
    border:1px solid #ffd6e7;
    outline:none;
}

input:focus, select:focus{
    border-color:#d63384;
    box-shadow:0 0 5px rgba(214,51,132,0.3);
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
    border-top:1px solid #ffe4ec;
}

/* ACTION BUTTONS */
a{
    text-decoration:none;
    padding:6px 10px;
    border-radius:8px;
    color:white;
    font-size:14px;
}
.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    font-weight:bold;
}
.edit{
    background:#0d6efd;
}

.delete{
    background:#dc3545;
}
</style>
</head>

<body>

<div class="container">

<h2>📦 Gestion des Commandes</h2>

<!-- FORM -->
<div class="card">

<form method="POST">

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit['idCommande'] ?>">
<?php endif; ?>

<input type="date" name="dateCommande"
value="<?= $edit['dateCommande'] ?? '' ?>" required>

<input type="number" step="0.01" name="montant"
value="<?= $edit['montant'] ?? '' ?>"
placeholder="Montant" required>

<select name="statut">
    <option value="en cours" <?= ($edit['statut'] ?? '')=='en cours'?'selected':'' ?>>En cours</option>
    <option value="livrée" <?= ($edit['statut'] ?? '')=='livrée'?'selected':'' ?>>Livrée</option>
    <option value="annulée" <?= ($edit['statut'] ?? '')=='annulée'?'selected':'' ?>>Annulée</option>
</select>

<input type="number" name="idClient"
value="<?= $edit['idClient'] ?? '' ?>"
placeholder="ID Client" required>

<button type="submit"
name="<?= $edit ? 'modifier' : 'ajouter' ?>">

<?= $edit ? "Modifier commande" : "Ajouter commande" ?>

</button>

</form>

</div>

<!-- TABLE -->
<table>

<tr>
<th>ID</th>
<th>Date</th>
<th>Montant</th>
<th>Statut</th>
<th>Client</th>
<th>Actions</th>
</tr>

<?php foreach($commandes as $c): ?>
<tr>
<td><?= $c['idCommande'] ?></td>
<td><?= $c['dateCommande'] ?></td>
<td><?= $c['montant'] ?> FCFA</td>
<td><?= $c['statut'] ?></td>
<td><?= $c['nom'] ?> <?= $c['prenom'] ?></td>

<td>
<a class="edit" href="?edit=<?= $c['idCommande'] ?>">Edit</a>
<a class="delete" href="?delete=<?= $c['idCommande'] ?>" onclick="return confirm('Supprimer ?')">Del</a>
</td>
</tr>
<?php endforeach; ?>

</table>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>