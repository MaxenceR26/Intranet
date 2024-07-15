<?php
// Connexion à la base de données
require '../php/Sample/core/connection.php';

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Connexion échouée : " . $mysqli->connect_error);
}

// Récupérer les données du formulaire
$event_date = $_POST['event_date'];
$event_title = $_POST['event_title'];

// Validation des données
$event_date = $mysqli->real_escape_string($event_date);
$event_title = $mysqli->real_escape_string($event_title);

// Insérer l'événement dans la base de données
$sql = "INSERT INTO events (event_date, event_title) VALUES ('$event_date', '$event_title')";
if ($mysqli->query($sql) === TRUE) {
    echo "Événement ajouté avec succès!";
} else {
    echo "Erreur : " . $sql . "<br>" . $mysqli->error;
}

// Redirection vers le calendrier
header("Location: index.php?month=" . date('n', strtotime($event_date)) . "&year=" . date('Y', strtotime($event_date)));
exit();

// Fermer la connexion
$mysqli->close();
?>
