<?php
session_start();
require 'core/connection.php';
// echo  . $_POST['objet'] . $_POST['np'] . $_POST['explication'] . $_POST['color'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $obj = $_POST['objet'];
    $np = explode(' ', $_POST['np']);
    $explain = $_POST['explication'];
    $color = $_POST['color'];
    $archive = 0;
    $etat = 0;
    $ran_int = random_int(151241, 3512014);
    
    $stmt = $mysqli->prepare("INSERT INTO tickets (utilisateurs, raison, date_creation, archive, etat, foreignkey, nomAnnuaire, prenomAnnuaire, color) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $_SESSION['email'], $explain, $archive, $etat, $ran_int, $np[0], $np[1], $color);        

    if ($stmt->execute()) {
        echo "Ticket fait";
    } else {
        echo "erreur";
    }

}

?>