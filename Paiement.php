<?php
require "connexion.php";

$message = "";

/* ================= FACTURES ================= */
$factures = $pdo->query("
    SELECT f.idFacture, c.idCommande
    FROM Facture f
    JOIN Commande c ON f.idCommande = c.idCommande
")->fetchAll(PDO::FETCH_ASSOC);

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("DELETE FROM Paiement WHERE idPaiement=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: Paiement.php");
    exit;
}

/* ================= EDIT ================= */
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("SELECT * FROM Paiement WHERE idPaiement=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ================= ADD ================= */
if (isset($_POST['ajouter'])) {

    $stmt = $pdo->prepare("
        INSERT INTO Paiement(datePaiement, montant, typePaiement, idFacture)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $_POST['datePaiement'],
        $_POST['montant'],
        $_POST['typePaiement'],
        $_POST['idFacture']
    ]);

    header("Location: Paiement.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['modifier'])) {

    $stmt = $pdo->prepare("
        UPDATE Paiement
        SET datePaiement=?, montant=?, typePaiement=?, idFacture=?
        WHERE idPaiement=?
    ");

    $stmt->execute([
        $_POST['datePaiement'],
        $_POST['montant'],
        $_POST['typePaiement'],
        $_POST['idFacture'],
        $_POST['id']
    ]);

    header("Location: Paiement.php");
    exit;
}

/* ================= LIST ================= */
$paiements = $pdo->query("
    SELECT * FROM Paiement ORDER BY idPaiement DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Paiements</title>

<style>

body{
    font-family:Segoe UI;
    margin:0;
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

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
    margin-bottom:20px;
}

/* INPUT */
input, select{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:10px;
    border:1px solid #ffd6e7;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#d63384;
    color:white;
    font-weight:bold;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
}

th{
    background:#ffe4ec;
    color:#d63384;
    padding:12px;
}

td{
    text-align:center;
    padding:12px;
    border-bottom:1px solid #f3f3f3;
}

/* ACTIONS */
a{
    text-decoration:none;
    padding:6px 10px;
    border-radius:6px;
    color:white;
}

.edit{ background:#0d6efd; }
.delete{ background:#dc3545; }

/* BACK */
.back{
    display:block;
    text-align:center;
    margin-top:20px;
    color:#d63384;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="container">

<h2>💳 Gestion Paiements</h2>

<!-- FORM -->
<div class="card">

<form method="POST">

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit['idPaiement'] ?>">
<?php endif; ?>

<input type="date" name="datePaiement"
value="<?= $edit['datePaiement'] ?? '' ?>" required>

<input type="number" step="0.01" name="montant"
value="<?= $edit['montant'] ?? '' ?>" placeholder="Montant" required>

<select name="typePaiement" required>
    <option value="Liquide">Liquide</option>
    <option value="Carte">Carte</option>
    <option value="Virement">Virement</option>
</select>

<!-- FACTURE LINK -->
<select name="idFacture" required>
    <option value="">-- Facture --</option>
    <?php foreach($factures as $f): ?>
        <option value="<?= $f['idFacture'] ?>">
            Facture #<?= $f['idFacture'] ?> (Cmd #<?= $f['idCommande'] ?>)
        </option>
    <?php endforeach; ?>
</select>

<button type="submit" name="<?= $edit ? 'modifier' : 'ajouter' ?>">
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
<th>Type</th>
<th>Facture</th>
<th>Actions</th>
</tr>

<?php foreach($paiements as $p): ?>
<tr>

<td><?= $p['idPaiement'] ?></td>
<td><?= $p['datePaiement'] ?></td>
<td><?= $p['montant'] ?> FCFA</td>
<td><?= $p['typePaiement'] ?></td>
<td>#<?= $p['idFacture'] ?></td>

<td>
<a class="edit" href="?edit=<?= $p['idPaiement'] ?>">Edit</a>
<a class="delete" href="?delete=<?= $p['idPaiement'] ?>"
onclick="return confirm('Supprimer ?')">Del</a>
</td>

</tr>
<?php endforeach; ?>

</table>

<a class="back" href="gerant_dashboard.php">⬅ Retour</a>

</div>

</body>
</html>