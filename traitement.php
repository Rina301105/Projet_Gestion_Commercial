<?php
session_start();
require "connexion.php";
/* Vérification formulaire */
if(!isset($_POST['login']) || !isset($_POST['password'])){
    header("Location: index.php");
    exit();
}
$login = trim($_POST['login']);
$password = $_POST['password'];
/* ================= GERANT ================= */
$stmt = $pdo->prepare("SELECT * FROM Gerant WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
/* ================= CLIENT ================= */
if(!$user){
    $stmt = $pdo->prepare("SELECT * FROM Client WHERE telephone = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
/* ================= USER NON TROUVÉ ================= */
if(!$user){
    $_SESSION['error'] = "Identifiant incorrect";
    header("Location: index.php");
    exit();
}
/* ================= MOT DE PASSE ================= */
if(!password_verify($password, $user['motDePasse'])){
    $_SESSION['error'] = "Mot de passe incorrect";
    header("Location: index.php");
    exit();
}
/* ================= SESSION ================= */
$_SESSION['user'] = $user;
/* ================= REDIRECTION ================= */
if(isset($user['idGerant'])){
    header("Location: gerant_dashboard.php");
} else {
    header("Location: client_dashboard.php");
}
exit();
?>