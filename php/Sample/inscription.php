<?php

require_once 'core/connection.php';

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

$stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['code' => 0, 'message' => 'Username exists']);
    $stmt->close();
    exit;
}
$stmt->close();


$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['code' => 0, 'message' => 'Emails exists']);
    $stmt->close();
    exit;
}
$stmt->close();

// Préparation de la requête SQL pour insérer l'utilisateur
$stmt = $mysqli->prepare("INSERT INTO users (firstname, username, email, lastname, img, idroles, numero, fonctions, bio, poste, `last-conn`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssss", $firstname, $username, $email, $lastname, $img, $idroles, $numero, $fonctions, $bio, $poste, $date, $hashedPassword);

// Exécution de la requête et gestion des erreurs
if ($stmt->execute()) {
    
    echo json_encode(['code' => 1, 'message' => 'User successfully created']);
} else {
    echo json_encode(['code' => 405, 'message' => 'Error on created account']);
}

$stmt->close();
$mysqli->close();



?>
