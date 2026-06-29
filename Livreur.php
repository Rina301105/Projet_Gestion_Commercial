<?php
require "connexion.php";

$msg = "";

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("DELETE FROM Livreur WHERE idLivreur=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: Livreur.php");
    exit;
}

/* ================= EDIT LOAD ================= */
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("SELECT * FROM Livreur WHERE idLivreur=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* ================= ADD ================= */
if (isset($_POST['ajouter'])) {

    $stmt = $pdo->prepare("
        INSERT INTO Livreur(nom, prenom, telephone, matriculeMoto)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['matriculeMoto']
    ]);

    header("Location: Livreur.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['modifier'])) {

    $stmt = $pdo->prepare("
        UPDATE Livreur
        SET nom=?, prenom=?, telephone=?, matriculeMoto=?
        WHERE idLivreur=?
    ");

    $stmt->execute([
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['matriculeMoto'],
        $_POST['id']
    ]);

    header("Location: Livreur.php");
    exit;
}

/* ================= LIST ================= */
$livreurs = $pdo->query("SELECT * FROM Livreur")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Livreurs</title>

<style>

body{
    font-family:"Segoe UI", Arial;
    background:linear-gradient(135deg,#ffe4ec,#ffd6f3);
    margin:0;
    padding:0;
}

.container{
    width:90%;
    max-width:900px;
    margin:40px auto;
}

h2{
    text-align:center;
    color:#c2185b;
    font-size:32px;
}

/* FORM */
form{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(194,24,91,0.15);
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:12px;
    border:1px solid #f8bbd0;
}

input:focus{
    border-color:#ec4899;
    outline:none;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(90deg,#ec4899,#db2777);
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
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

th{
    background:#ffe4ec;
    color:#c2185b;
    padding:12px;
}

td{
    text-align:center;
    padding:12px;
    border-top:1px solid #fce7f3;
}

/* ACTIONS */
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
    background:#3b82f6;
}

.delete{
    background:#ef4444;
}

</style>
</head>

<body>

<div class="container">

<h2>🚚 Gestion des Livreurs</h2>

<!-- FORM -->
<form method="POST">

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit['idLivreur'] ?>">
<?php endif; ?>

<input type="text" name="nom"
value="<?= $edit['nom'] ?? '' ?>"
placeholder="Nom" required>

<input type="text" name="prenom"
value="<?= $edit['prenom'] ?? '' ?>"
placeholder="Prénom" required>

<input type="text" name="telephone"
value="<?= $edit['telephone'] ?? '' ?>"
placeholder="Téléphone" required>

<input type="text" name="matriculeMoto"
value="<?= $edit['matriculeMoto'] ?? '' ?>"
placeholder="Matricule Moto" required>

<button type="submit"
name="<?= $edit ? 'modifier' : 'ajouter' ?>">

<?= $edit ? "Modifier Livreur" : "Ajouter Livreur" ?>

</button>

</form>

<!-- TABLE -->
<table>

<tr>
<th>ID</th>
<th>Nom</th>
<th>Prénom</th>
<th>Téléphone</th>
<th>Matricule</th>
<th>Actions</th>
</tr>

<?php foreach($livreurs as $l): ?>
<tr>
<td><?= $l['idLivreur'] ?></td>
<td><?= $l['nom'] ?></td>
<td><?= $l['prenom'] ?></td>
<td><?= $l['telephone'] ?></td>
<td><?= $l['matriculeMoto'] ?></td>

<td>
<a class="edit" href="?edit=<?= $l['idLivreur'] ?>">Edit</a>
<a class="delete" href="?delete=<?= $l['idLivreur'] ?>" onclick="return confirm('Supprimer ?')">Del</a>
</td>
</tr>
<?php endforeach; ?>

</table>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>
