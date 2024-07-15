<?php
// Vérifier si des données ont été soumises
require_once '../Sample/core/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier chaque champ et le mettre à jour s'il est rempli
    $fonctions = $_POST["fonctions"];
    $email = $_POST["email"];
    $numero = $_POST["numero"];

    // Vérifier si les champs ne sont pas vides avant de les mettre à jour
    if (!empty($fonctions)) {
        $sql = "UPDATE users SET fonctions = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $fonctions, $_SESSION['idFiche']);
        if ($stmt->execute()) {
            header("Location: ../../intranet.php");
        }
        $stmt->close();
    }

    if (!empty($email)) {
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $email, $_SESSION['idFiche']);
        if ($stmt->execute()) {
            header("Location: ../../intranet.php");
        }
        $stmt->close();
    }

    if (!empty($numero)) {
        $sql = "UPDATE users SET numero = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $numero, $_SESSION['idFiche']);
        if ($stmt->execute()) {
            header("Location: ../../intranet.php");
        }
        $stmt->close();
    }
    exit();
}
?>