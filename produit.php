<?php
require "connexion.php";

/* ================= SECURITE ================= */


/* ================= AJOUT ================= */
if(isset($_POST['ajouter'])){
    $stmt = $pdo->prepare("
        INSERT INTO Produit(nom, prix, quantite)
        VALUES(?,?,?)
    ");

    $stmt->execute([
        $_POST['nom'],
        $_POST['prix'],
        $_POST['quantite']
    ]);

    header("Location: produit.php");
    exit();
}

/* ================= SUPPRESSION ================= */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM Produit WHERE idProduit=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: produit.php");
    exit();
}

/* ================= EDIT ================= */
$edit = null;

if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM Produit WHERE idProduit=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* ================= MODIFIER ================= */
if(isset($_POST['modifier'])){
    $stmt = $pdo->prepare("
        UPDATE Produit
        SET nom=?, prix=?, quantite=?
        WHERE idProduit=?
    ");

    $stmt->execute([
        $_POST['nom'],
        $_POST['prix'],
        $_POST['quantite'],
        $_POST['id']
    ]);

    header("Location: produit.php");
    exit();
}

/* ================= LISTE ================= */
$produits = $pdo->query("SELECT * FROM Produit ORDER BY idProduit DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Produits</title>

<style>

body{
    margin:0;
    font-family:"Segoe UI", Arial;
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
    font-size:13px;
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

</style>

</head>

<body>

<div class="container">

<h2>🛍️ Gestion des Produits</h2>

<!-- FORM -->
<div class="card">

<form method="POST">

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit['idProduit'] ?>">
<?php endif; ?>

<input type="text" name="nom"
placeholder="Nom du produit"
value="<?= $edit['nom'] ?? '' ?>"
required>

<input type="number" name="prix"
placeholder="Prix"
value="<?= $edit['prix'] ?? '' ?>"
required>

<input type="number" name="quantite"
placeholder="Quantité"
value="<?= $edit['quantite'] ?? '' ?>"
required>

<button type="submit"
name="<?= $edit ? 'modifier' : 'ajouter' ?>">

<?= $edit ? "✏️ Modifier le produit" : "➕ Ajouter le produit" ?>

</button>

</form>

</div>

<!-- TABLE -->
<table>

<tr>
<th>ID</th>
<th>Nom</th>
<th>Prix</th>
<th>Stock</th>
<th>Actions</th>
</tr>

<?php foreach($produits as $p): ?>

<tr>
<td><?= $p['idProduit'] ?></td>
<td><?= $p['nom'] ?></td>
<td><?= $p['prix'] ?> FCFA</td>
<td><?= $p['quantite'] ?></td>

<td>
<a class="edit" href="?edit=<?= $p['idProduit'] ?>">Modifier</a>
<a class="del" href="?delete=<?= $p['idProduit'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
</td>

</tr>

<?php endforeach; ?>

</table>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>