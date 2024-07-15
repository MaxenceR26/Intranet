<?php
session_start(); // Démarre la session PHP

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Redirige vers la page de connexion ou affiche un message d'erreur
    header("Location: login.php"); // Redirection vers la page de connexion
    exit(); // Arrête l'exécution du script
}

function getDomainName($url) {
    $parsedUrl = parse_url($url);
    $host = $parsedUrl['host'];
    
    // Enlever 'www.' s'il est présent
    if (substr($host, 0, 4) == "www.") {
        $host = substr($host, 4);
    }

    // Séparer par les points et retourner la première partie
    $parts = explode('.', $host);
    return $parts[0];
}


// Vérifie si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $linkOne = trim($_POST['linkOne']);
    $linkTwo = trim($_POST['linkTwo']);
    $linkThree = trim($_POST['linkThree']);
    $linkFour = trim($_POST['linkFour']);
    $linkFive = trim($_POST['linkFive']);
    $linkSix = trim($_POST['linkSix']);

    // Connexion à la base de données (à adapter selon votre configuration)
    require 'core/connection.php'; // Inclut le fichier de connexion à la base de données

    // Fonction pour insérer ou mettre à jour un lien
    function insertOrUpdateLink($mysqli, $title, $url, $email) {
        $count = null;
        // Vérifie si le lien existe déjà pour cet utilisateur
        $stmt_check = $mysqli->prepare("SELECT COUNT(*) FROM link WHERE title = ? AND email = ?");
        $stmt_check->bind_param("ss", $title, $email);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // Met à jour l'URL du lien existant
            $stmt_update = $mysqli->prepare("UPDATE link SET url = ? WHERE title = ? AND email = ?");
            $stmt_update->bind_param("sss", $url, $title, $email);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            // Insère le nouveau lien s'il n'existe pas encore
            $stmt_insert = $mysqli->prepare("INSERT INTO link (title, url, email) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $title, $url, $email);
            $stmt_insert->execute();
            $stmt_insert->close();
        }
    }

    // Appelle la fonction pour chaque lien non vide
    $links = array(
        array("title" => getDomainName($linkOne), "url" => $linkOne),
        array("title" => getDomainName($linkTwo), "url" => $linkTwo),
        array("title" => getDomainName($linkThree), "url" => $linkThree),
        array("title" => getDomainName($linkFour), "url" => $linkFour),
        array("title" => getDomainName($linkFive), "url" => $linkFive),
        array("title" => getDomainName($linkSix), "url" => $linkSix)
    );

    foreach ($links as $link) {
        $title = $link['title'];
        $url = $link['url'];

        // Vérifie si le lien n'est pas vide avant d'insérer ou de mettre à jour
        if (!empty($url)) {
            insertOrUpdateLink($mysqli, $title, $url, $_SESSION['email']);
            header('Location: ../../intranet.php');
        }
    }

    // Ferme la connexion à la base de données
    $mysqli->close();
}
?>
