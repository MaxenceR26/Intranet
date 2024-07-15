<?php
require 'php/Sample/core/connection.php';
require_once 'php/Sample/core/permission.php';

// Vérification de la session
if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../../index.html');
    exit; // Arrêt de l'exécution du script après la redirection
}

// Requête SQL pour récupérer les trois dernières actualités
$sql = "SELECT titre, head, body, texts, dates, image FROM actualites ORDER BY id DESC LIMIT 3";
$result = $mysqli->query($sql);

if ($result) {
    // Initialisation d'un tableau pour stocker les actualités
    $actualites = [];

    // Récupération des résultats dans un tableau associatif
    while ($row = mysqli_fetch_assoc($result)) {
        $actualites[] = $row;
    }

    // Stockage des actualités dans la session (facultatif)
    $_SESSION['actualites'] = $actualites;
} else {
    echo "Erreur dans l'exécution de la requête : " . $mysqli->error;
}

// Fermeture de la connexion MySQL
$mysqli->close();
?>