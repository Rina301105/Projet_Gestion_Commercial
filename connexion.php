<?php

try {

    $pdo = new PDO(
        "mysql:host=mysql;dbname=groupe1;charset=utf8",
        "root",
        "root"
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Erreur de connexion : " . $e->getMessage());

}

?>