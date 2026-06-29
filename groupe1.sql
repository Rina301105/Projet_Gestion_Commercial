CREATE DATABASE IF NOT EXISTS groupe1;
USE groupe1;

/* CLIENT */
CREATE TABLE Client (
    idClient INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    adresse VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    motDePasse VARCHAR(255) NOT NULL
);

/* GERANT */
CREATE TABLE Gerant (
    idGerant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20),
    login VARCHAR(50) UNIQUE NOT NULL,
    motDePasse VARCHAR(255) NOT NULL
);

/* LIVREUR */
CREATE TABLE Livreur (
    idLivreur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20),
    matriculeMoto VARCHAR(30)
);

/* PRODUIT */
CREATE TABLE Produit (
    idProduit INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    quantite INT NOT NULL
);

/* COMMANDE */
CREATE TABLE Commande (
    idCommande INT PRIMARY KEY AUTO_INCREMENT,
    dateCommande DATE NOT NULL,
    montant DECIMAL(10,2) DEFAULT 0,
    statut VARCHAR(30) DEFAULT 'en attente',

    idClient INT NOT NULL,
    idLivreur INT NULL,
    idGerant INT NULL,

    FOREIGN KEY (idClient) REFERENCES Client(idClient),
    FOREIGN KEY (idLivreur) REFERENCES Livreur(idLivreur),
    FOREIGN KEY (idGerant) REFERENCES Gerant(idGerant)
);

/* DETAIL COMMANDE */
CREATE TABLE DetailCommande (
    idDetail INT AUTO_INCREMENT PRIMARY KEY,
    idProduit INT,
    idCommande INT,
    quantite INT NOT NULL,
    montant DECIMAL(10,2),

    FOREIGN KEY (idProduit) REFERENCES Produit(idProduit) ON DELETE CASCADE,
    FOREIGN KEY (idCommande) REFERENCES Commande(idCommande) ON DELETE CASCADE
);

/* FACTURE */
CREATE TABLE Facture (
    idFacture INT PRIMARY KEY AUTO_INCREMENT,
    dateFacture DATE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    idCommande INT UNIQUE,

    FOREIGN KEY (idCommande) REFERENCES Commande(idCommande)
);

/* PAIEMENT */
CREATE TABLE Paiement (
    idPaiement INT PRIMARY KEY AUTO_INCREMENT,
    datePaiement DATE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    typePaiement VARCHAR(30) NOT NULL,
    idFacture INT,

    FOREIGN KEY (idFacture) REFERENCES Facture(idFacture)
);