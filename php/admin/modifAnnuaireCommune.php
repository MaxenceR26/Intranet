<?php
session_start(); // Démarre la session

if (!isset($_SESSION['state'])) {
    header('Location: ../../index.html');
    exit(); // Assurez-vous de sortir du script après une redirection
}

require_once '../Sample/core/connection.php'; // Inclure votre fichier de connexion à la base de données

$id = $_POST["identifiants"] ?? null;

if (!$id) {
    die("Identifiant manquant.");
}

// Préparer la requête pour éviter les injections SQL
$sql = "SELECT * FROM ticketsmodif WHERE foreignkey = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $numero = $row["numport"];
    $adresse_postale = $row["adresse_postale"];
    $email = $row['email'];
    $nomAnnuaire = $row['nomAnnuaire'];
} else {
    die("Aucun enregistrement trouvé pour la clé étrangère donnée.");
}

// Mettre à jour les champs dans la table 'services'
$fieldsToUpdate = [];
$params = [];
$types = "";

// Vérification des champs avant la mise à jour
if ($email != "default@example.com") {
    $fieldsToUpdate[] = "mail = ?";
    $params[] = $email;
    $types .= "s";
}
if ($numero != "000000000") {
    $fieldsToUpdate[] = "telephone = ?";
    $params[] = $numero;
    $types .= "s";
}
if ($adresse_postale != "Unknown") {
    $fieldsToUpdate[] = "adresse_postale = ?";
    $params[] = $adresse_postale;
    $types .= "s";
}

// Ajouter les valeurs pour les paramètres de nom et prénom
$params[] = $nomAnnuaire;
$types .= "s";

if (!empty($fieldsToUpdate)) {
    $sql = "UPDATE communes SET " . implode(", ", $fieldsToUpdate) . " WHERE commune = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "Mise à jour réussie.";
    } else {
        echo "Erreur lors de la mise à jour : " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Aucune donnée à mettre à jour.";
}


?>
