<?php

require_once 'core/connection.php';

session_start();

if (!isset($_SESSION['state'])) {
    session_destroy();
    header('Location: ../../index.html');
}

$_SESSION['state'] = false;
$date = date("d-m-Y H:i");
$username = htmlspecialchars($_GET["username"], ENT_QUOTES, 'UTF-8');
$password = $_GET["password"];
$firstname = htmlspecialchars($_GET["firstname"], ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($_GET["lastname"], ENT_QUOTES, 'UTF-8');
$email = filter_var($_GET["email"], FILTER_SANITIZE_EMAIL);
$pswd = $_GET["password"];
$fonctions = htmlspecialchars($_GET["fonctions"], ENT_QUOTES, 'UTF-8');
$numero = htmlspecialchars($_GET["phonenumber"], ENT_QUOTES, 'UTF-8');
$bio = ""; // Vous pouvez aussi échapper cette valeur si elle provient d'une entrée utilisateur
$img = 'images/icone-utilisateur.png';
$idroles = 1;
$poste = 'Consultant';

// Hachage du mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Préparation de la requête SQL
$stmt = $mysqli->prepare("INSERT INTO users (firstname, username, email, lastname, img, idroles, numero, fonctions, bio, poste, `last-conn`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssss", $firstname, $username, $email, $lastname, $img, $idroles, $numero, $fonctions, $bio, $poste, $date, $hashedPassword);

// Exécution de la requête et gestion des erreurs
if ($stmt->execute()) {
    echo "Utilisateur créé avec succès";
} else {
    echo "Erreur lors de la création de l'utilisateur";
}
?>
