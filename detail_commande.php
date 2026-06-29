<?php
require "connexion.php";

$message = "";

/* ================= AJOUT ================= */
if (isset($_POST['ajouter'])) {

    $idProduit = $_POST['idProduit'];
    $idCommande = $_POST['idCommande'];
    $quantite = $_POST['quantite'];

    try {
        $pdo->beginTransaction();

        // Produit
        $stmt = $pdo->prepare("SELECT quantite, prix FROM Produit WHERE idProduit=?");
        $stmt->execute([$idProduit]);
        $produit = $stmt->fetch();

        if (!$produit) {
            throw new Exception("Produit introuvable");
        }

        if ($produit['quantite'] < $quantite) {
            throw new Exception("Stock insuffisant");
        }

        $montant = $produit['prix'] * $quantite;

        // Insert détail
        $stmt = $pdo->prepare("
            INSERT INTO DetailCommande(idProduit,idCommande,quantite,montant)
            VALUES(?,?,?,?)
        ");
        $stmt->execute([$idProduit,$idCommande,$quantite,$montant]);

        // update stock
        $stmt = $pdo->prepare("
            UPDATE Produit SET quantite = quantite - ?
            WHERE idProduit=?
        ");
        $stmt->execute([$quantite,$idProduit]);

        $pdo->commit();
        $message = "✨ Produit ajouté avec succès";

    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "❌ ".$e->getMessage();
    }
}

/* ================= SUPPRESSION ================= */
if (isset($_GET['delete'])) {

    $idp = $_GET['idp'];
    $idc = $_GET['idc'];

    $pdo->beginTransaction();

    // récupérer ligne
    $stmt = $pdo->prepare("SELECT * FROM DetailCommande WHERE idProduit=? AND idCommande=?");
    $stmt->execute([$idp,$idc]);
    $ligne = $stmt->fetch();

    if ($ligne) {

        // restituer stock
        $stmt = $pdo->prepare("
            UPDATE Produit SET quantite = quantite + ?
            WHERE idProduit=?
        ");
        $stmt->execute([$ligne['quantite'],$ligne['idProduit']]);

        // delete
        $stmt = $pdo->prepare("
            DELETE FROM DetailCommande WHERE idProduit=? AND idCommande=?
        ");
        $stmt->execute([$idp,$idc]);
    }

    $pdo->commit();

    header("Location: detail_commande.php");
    exit;
}

/* ================= LISTE ================= */
$details = $pdo->query("
    SELECT dc.*, p.nom
    FROM DetailCommande dc
    JOIN Produit p ON p.idProduit = dc.idProduit
")->fetchAll();

$produits = $pdo->query("SELECT * FROM Produit")->fetchAll();
$commandes = $pdo->query("SELECT * FROM Commande")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Détail Commande</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
    color:#3b2f3f;
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
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
    margin-bottom:25px;
}

/* INPUT */
select,input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border-radius:12px;
    border:1px solid #ffd6e7;
}

select:focus,input:focus{
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

/* MESSAGE */
.msg{
    text-align:center;
    font-weight:bold;
    color:#16a34a;
    margin-bottom:10px;
}

.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#d63384;
    background:transparent;
    font-weight:bold;
}
/* DELETE */
a{
    color:white;
    background:#ef4444;
    padding:6px 10px;
    border-radius:8px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="container">

<h2>💖 Détail Commande</h2>

<div class="msg"><?= $message ?></div>

<div class="card">
<form method="POST">

<select name="idCommande" required>
<option>Commande</option>
<?php foreach($commandes as $c): ?>
<option value="<?= $c['idCommande'] ?>">#<?= $c['idCommande'] ?></option>
<?php endforeach; ?>
</select>

<select name="idProduit" required>
<option>Produit</option>
<?php foreach($produits as $p): ?>
<option value="<?= $p['idProduit'] ?>">
<?= $p['nom'] ?>
</option>
<?php endforeach; ?>
</select>

<input type="number" name="quantite" placeholder="Quantité" required>

<button type="submit" name="ajouter">Ajouter</button>

</form>
</div>

<table>
<tr>
<th>Commande</th>
<th>Produit</th>
<th>Quantité</th>
<th>Montant</th>
<th>Action</th>
</tr>

<?php foreach($details as $d): ?>
<tr>
<td>#<?= $d['idCommande'] ?></td>
<td><?= $d['nom'] ?></td>
<td><?= $d['quantite'] ?></td>
<td><?= $d['montant'] ?> FCFA</td>
<td>
<a href="?delete=1&idp=<?= $d['idProduit'] ?>&idc=<?= $d['idCommande'] ?>">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>

</table>
<a class="back" href="gerant_dashboard.php">⬅ Retour</a>
</div>

</body>
</html>
