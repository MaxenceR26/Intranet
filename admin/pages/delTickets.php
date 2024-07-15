<?php
session_start();
if (!isset($_SESSION['state'])) {
    header('Location: ..\..\index.html');
}
require_once '../../php/Sample/core/connection.php';


$id = $_POST['identifiants'];
$etat = '1';

$current_page = $_POST['current_page'];

if ($current_page == 'tickets') {
    $sql = "DELETE FROM ticketsmodif WHERE foreignkey = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $sql = "UPDATE tickets SET etat = ? WHERE foreignkey = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $etat, $id);
        if ($stmt->execute()) {
            echo "Ticket supprimé avec succès";
        }else {
        echo "Erreur lors de la suppression des tickets de la table $table : " . $stmt->error;
        }   
    } 

    $stmt->close();
} else if ($current_page == 'archive') {
    $sql = "DELETE FROM tickets WHERE foreignkey = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Ticket supprimé avec succès";
    } else {
        echo "Erreur lors de la suppression des tickets de la table $table : " . $stmt->error;
    }

    $stmt->close();
}

?>
