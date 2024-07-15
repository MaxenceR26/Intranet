<?php
session_start();
if (!isset($_SESSION['state'])) {
    header('Location: ..\..\index.html');
}
require_once '../../php/Sample/core/connection.php';

$sql = "DELETE FROM tickets WHERE etat = ?";
$stmt = $mysqli->prepare($sql);
$etat = 1;
$stmt->bind_param("i", $etat);

if ($stmt->execute()) {
    echo "Tickets supprimés avec succès";
} else {
    echo "Erreur lors de la suppression des tickets : " . $stmt->error;
}

$stmt->close();
?>