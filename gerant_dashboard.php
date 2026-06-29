<?php
session_start();

/* ================= SECURITE ================= */
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$gerant = $_SESSION['user'];

/* valeurs sécurisées */
$nom = $gerant['nom'] ?? 'Gérant';
$login = $gerant['login'] ?? 'Non défini';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Gérant</title>

<style>

body{
    margin:0;
    font-family:Segoe UI, Arial;
    background:linear-gradient(135deg,#fff0f6,#ffe4ec);
}

/* HEADER */
.header{
    background:white;
    padding:25px;
    text-align:center;
    box-shadow:0 10px 25px rgba(214,51,132,0.15);
}

.header h1{
    color:#d63384;
    margin:0;
}

/* CONTAINER */
.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(214,51,132,0.12);
    margin-bottom:20px;
}

/* TITRES */
h3{
    color:#d63384;
}

/* BUTTONS */
.btn{
    display:inline-block;
    padding:12px 15px;
    margin:8px 8px 0 0;
    background:#d63384;
    color:white;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}

.btn:hover{
    opacity:0.85;
    transform:translateY(-2px);
}

</style>
</head>
<body>

<div class="header">
    <h1>💼 Tableau de bord Gérant</h1>
</div>

<div class="container">

<!-- INFOS -->
<div class="card">
    <h3>Bienvenue <?= htmlspecialchars($nom) ?></h3>
    <p>Login : <?= htmlspecialchars($login) ?></p>
</div>

<!-- ACTIONS -->
<div class="card">
    <h3>Actions</h3>

    <a class="btn" href="produit.php">🛍️ Produits</a>
    <a class="btn" href="client.php">👤 Clients</a>
    <a class="btn" href="commande.php">📦 Commandes</a>
    <a class="btn" href="facture.php">🧾 Factures</a>
    <a class="btn" href="paiement.php">💳 Paiements</a>
    <a class="btn" href="livreur.php">🚚 Livreurs</a>
    <a class="btn" href="detail_commande.php">📋 Détails Commandes</a>
    <a class="btn" href="logout.php">🚪 Déconnexion</a>

</div>

</div>

</body>
</html>