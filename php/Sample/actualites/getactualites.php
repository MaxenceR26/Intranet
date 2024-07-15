<?php
require 'php/Sample/core/connection.php';
require_once 'php/Sample/core/permission.php';

if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../../index.html');
} else {
    $sql = "SELECT titre, head, body, texts, dates, image FROM actualites WHERE id = (SELECT MAX(id) FROM actualites);";
    $result = $mysqli->query($sql);

    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $_SESSION['titre'] = $row['titre'];
            $_SESSION['head'] = $row['head'];
            $_SESSION['body'] = $row['body'];
            $_SESSION['texts'] = $row['texts'];
            $_SESSION['dates'] = $row['dates'];
            $_SESSION['image_actu'] = $row['image'];
            
        }
    } else {
        echo "Erreur dans l'exécution de la requête : " . $mysqli->error;
    }
}


