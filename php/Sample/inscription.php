<?php

require_once 'core/connection.php';

session_start();

$_SESSION['state'] = false;
$date = date("d-m-Y H:i");
$username = $_GET["username"];
$password = $_GET["password"];
$firstname = $_GET["firstname"];
$lastname = $_GET["lastname"];
$email = $_GET["email"];
$pswd = $_GET["password"];
$fonctions = $_GET["fonctions"];
$numero = $_GET["phonenumber"];
$bio = "";
$img = 'images/icone-utilisateur.png';
$idroles = 1;
$poste = 'Consultant';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (firstname, username, email, lastname, img, idroles, numero, fonctions, bio, poste, `last-conn`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssss", $firstname, $username, $email, $lastname, $img, $idroles, $numero, $fonctions, $bio, $poste, $date, $hashedPassword);

if ($stmt->execute()) {
    echo "Utilisateur créé avec succès";
} else {
    echo "Erreur lors de la création de l'utilisateur";
}

