<?php
session_start(); // Démarrez la session

if (!isset($_SESSION['state'])) {
    header('Location: ../../index.html');
    exit(); // Assurez-vous de sortir du script après une redirection
}

require_once '../Sample/core/connection.php'; // Inclure votre fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $raison = trim($_POST['raison']);
    $page = trim($_POST['page']);
    $email = $_SESSION['email'];
    $archive = 0;
    $etat = 0;

    if (empty($raison) || empty($page) || empty($email)) {
        die("Tous les champs sont obligatoires.");
    }!

    $ran_int = random_int(151241, 3512014);
    // Préparer et exécuter la requête d'insertion
    if ($page == 'actualites') {
        $nomAnnuaire = "None";
        $prenomAnnuaire = "None";
        $stmt = $mysqli->prepare("INSERT INTO tickets (utilisateurs, pages, raison, date_creation, archive, etat, foreignkey, nomAnnuaire, prenomAnnuaire) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $email, $page, $raison, $archive, $etat, $ran_int, $nomAnnuaire, $prenomAnnuaire);    
    } else {
        $stmt = $mysqli->prepare("INSERT INTO tickets (utilisateurs, pages, raison, date_creation, archive, etat, foreignkey, nomAnnuaire, prenomAnnuaire) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $email, $page, $raison, $archive, $etat, $ran_int, $_SESSION['nomAnnuaire'], $_SESSION['prenomAnnuaire']);

    }
    
    if ($stmt->execute()) {
        if ($page == 'actualites') {
            header('Location: ../../intranet.php');
        } else {
            // Récupérer les valeurs POST
            $emailInput = isset($_POST['email']) ? $_POST['email'] : '';
            $numportInput = isset($_POST['numero']) ? $_POST['numero'] : '';
            $numextInput = isset($_POST['numeroext']) ? $_POST['numeroext'] : '';
            $dirfixeInput = isset($_POST['dirfixe']) ? $_POST['dirfixe'] : '';
            $adresseInput = isset($_POST['adresse']) ? $_POST['adresse'] : '';

            // Préparer la requête SQL
            $stmt = $mysqli->prepare("INSERT INTO ticketsmodif (foreignkey, email, numport, numext, dirfixe, adresse_postale, nomAnnuaire, prenomAnnuaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // Utiliser des variables intermédiaires pour les valeurs NULL
            $email = !empty($emailInput) ? $emailInput : 'default@example.com';
            $numport = !empty($numportInput) ? $numportInput : '0000000000';
            $numext = !empty($numextInput) ? $numextInput : '0000000000';
            $dirfixe = !empty($dirfixeInput) ? $dirfixeInput : '0000000000';
            $adresse = !empty($adresseInput) ? $adresseInput : 'Unknown';
            // Utiliser NULL si une valeur est vide
            $stmt->bind_param(
                "isssssss",
                $ran_int,
                $email,
                $numport,
                $numext,
                $dirfixe,
                $adresse, 
                $_SESSION['nomAnnuaire'], 
                $_SESSION['prenomAnnuaire']
            );

            if ($stmt->execute()) {
                header('Location: ../../intranet.php');
            }
        }
        
    } else {
        echo "Erreur : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $mysqli->close();
} else {
    echo "Méthode de requête invalide.";
}
?>
