<?php
session_start();
$phrase = $_POST['phrase'];
$image = $_POST['image'];
$name = $_SESSION['username'];

require_once 'core/connection.php'; // Inclure votre fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $mysqli->prepare("INSERT INTO galerie (bio, link, `name`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $phrase, $image, $name);    
    $stmt->execute();
}
?>