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
$fonctions = $_GET["fonctions"];
$numero = $_GET["phonenumber"];
$bio = "";
$img = 'images/icone-utilisateur.png';
$idroles = 1;

$sql = "SELECT nom FROM roles WHERE idroles = $idroles";
$result = $mysqli->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $poste = $row['nom'];
    }
}

$stmt = $mysqli->prepare("INSERT INTO users (firstname, username, email, lastname, img, idroles, numero, fonctions, bio, poste, `last-conn`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $firstname, $username, $email, $lastname, $img, $idroles, $numero, $fonctions, $bio, $poste, $date);        

if ($stmt->execute()) {
    echo "Ticket fait";
} else {
    echo "erreur";
}


